var app = angular.module('papermill');

app.controller('LoginCtrl', ['$scope', '$location', 'AuthService', function($scope, $location, AuthService) {

    $scope.credentials = {};

    $scope.login = function() {
        console.log($scope.credentials);
        AuthService.login($scope.credentials);
    };

    $scope.register = function() {
    	$location.path('/');
    };
}]);
