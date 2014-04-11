var app = angular.module('papermill');

app.controller('SigninCtrl', ['$scope', 'AuthService', function($scope, AuthService) {

    $scope.profile = {};

    $scope.signin = function() {
        console.log($scope.profile);
        AuthService.signin($scope.profile);
    }
}]);
