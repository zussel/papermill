'use strict';

angular.module('papermill').factory("AuthService", function ($q) {
    var user = {
        authenticated: false,
        id: null,
        profile: {}
    };

    return {
        login: function(credentials) {

        },
        logout: function() {

        },
        signin: function(profile) {

        },
        isAuthenticated: function () {
            var defer = $q.defer();
            if (user.id && user.authenticated) {
                console.log("user authenticated: " + user.id);
                defer.resolve(user);
            } else {
                console.log("user isn't authenticated");
                defer.reject();
                /*
                console.log("requesting user info...");
                PortalApi.user().success(function (data) {
                    if (data.id != null) {
                        self.user = data;
                        console.log("User Info Obtained: " + self.user);
                        defer.resolve(self.user);
                    } else {
                        console.log("Failed to get user info");
                        defer.reject(data);
                    }
                }).error(function (data){
                    console.log("401 Response. Rejecting defer.")
                    defer.reject(data);
                });
                */
            }
            return defer.promise;
        }
    }
});
