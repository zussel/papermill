var papermill = angular.module('papermill', ['ngRoute', 'ui.bootstrap.modal'])

papermill.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
        redirectTo: '/login'
    }).when('/papers', {
        templateUrl: 'app/modules/papers/partials/papers.html',
        controller: 'PapersCtrl'
    }).when('/login', {
        templateUrl: 'app/modules/login/partials/login.html',
        controller: 'LoginCtrl'
    }).otherwise({
        redirectTo: '/login'
    });
}]);

