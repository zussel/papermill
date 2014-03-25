var papermill = angular.module('papermill', ['ngRoute', 'ngResource', 'ui.bootstrap.modal'])

papermill.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
        redirectTo: '/papers'
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

