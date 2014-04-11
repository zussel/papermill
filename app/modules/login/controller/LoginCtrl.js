var app = angular.module('papermill');

app.controller('LoginCtrl', ['$scope', 'AuthService', function($scope, AuthService) {

    $scope.credentials = {};

    $scope.login = function() {
        console.log($scope.credentials);
        AuthService.login($scope.credentials);
    }
}]);
