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
        templateUrl: 'app/modules/login/partials/signin.html',
        controller: 'SigninCtrl'
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
    $httpProvider.interceptors.push(function($q, $location, $window) {
        return {
            'request': function(request) {
                request.headers = request.headers || {};
                request.headers['Accept-Language'] = "de-de";
                if ($window.sessionStorage.token) {
//                    request.headers.Authorization = 'Bearer ' + $window.sessionStorage.token;
                    request.headers['Authorization'] = 'Bearer ' + $window.sessionStorage.token;
                }
                return request;
            },
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

papermill.config(function(uiSelectConfig) {
    uiSelectConfig.theme = 'bootstrap';
});
