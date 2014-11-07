'use strict';

angular.module('papermill').factory("AuthService", function ($q, $http, $location, $window) {
    var user = {
        id: null,
        profile: null
    };

    return {
        user: user,
        login: function(credentials) {
            $http.post('/api/auth/login', credentials)
                .success(function(data) {
                    /*
                     * login successfull
                     * store token and user id
                     */
                    $window.sessionStorage.token = data.token;
                    $location.path('/papers');
                })
                .error(function() {
                    console.log('couldn\'t login');
                });
        },
        logout: function() {
            $http.get('/api/auth/logout/' + user.id)
                .success(function(data) {
                    user.id = null;
                    user.profile = null;
                    delete $window.sessionStorage.token;
                    $location.path('/login');
                })
                .error(function() {
                    console.log('couldn\'t logout');
                })

        },
        signin: function(profile) {
            $http.post('/api/auth/signin', profile)
                .success(function(data) {
                    $location.path('/login');
                })
                .error(function(data) {
                    console.log(data);
                });
        },
        loggedIn: function() {
            return user.id !== null;
        },
        isAuthenticated: function () {
            var defer = $q.defer();
            if (user.id && user.profile) {
                /*
                 * user is logged in and
                 * profile data was retrieved
                 */
                console.log("user authenticated: " + user.id);
                defer.resolve(user);
            } else if (!user.id && !user.profile) {
                /*
                 * user is logged in, but
                 * profile wasn't retrieved yet
                 * retrieve profile now
                 */
                var payload = angular.fromJson($window.atob($window.sessionStorage.token.split('.')[1]));
                user.id = payload.id;
                $http.get('/api/user/' + user.id + '/profile').success(function(data) {
                    user.profile = data;
                    defer.resolve(user);
                }).error(function(error) {
                    defer.reject(error);
                })
            } else {
                /*
                 * user isn't logged in
                 */
                console.log("user isn't authenticated");
                defer.reject();
            }
            return defer.promise;
        }
    }
});
