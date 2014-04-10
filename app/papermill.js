var papermill = angular.module('papermill', [
    'ngRoute',
    'ngResource',
    'ui.bootstrap.modal',
    'angularFileUpload',
    'ngTagsInput'
]);

papermill.config(['$routeProvider', function($routeProvider) {
    var requiresAuthentication = {
        userInfo: function(AuthService) {
            return AuthService.isAuthenticated();
        }
    };

    $routeProvider.when('/', {
        redirectTo: '/papers'
    }).when('/papers', {
        templateUrl: 'app/modules/papers/partials/papers.html',
        controller: 'PapersCtrl',
        resolve: requiresAuthentication
    }).when('/login', {
        templateUrl: 'app/modules/login/partials/login.html',
        controller: 'LoginCtrl'
    }).otherwise({
        redirectTo: '/login'
    });
}]);

papermill.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push(function($q, $location) {
        return {
            'response': function (response) {
                // do something on success
                return response;
            },
            'responseError': function(rejection) {
                if (rejection.status == 401) {
                    // Zur Login-Seite
                    $location.path('/login');
                }
                return $q.reject(rejection);
            }
        }
    });
}]);