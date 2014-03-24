var papermill = angular.module('papermill', ['ngRoute', 'ui.bootstrap.modal'])

papermill.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
        redirectTo: '/papers'
    }).when('/papers', {
        templateUrl: 'app/modules/papers/partials/papers.html',
        controller: 'PapersCtrl'
    }).otherwise({
        redirectTo: '/'
    });
}]);

