var app = angular.module('papermill');

app.controller('LoginCtrl', ['$scope', '$location', 'AuthService', function($scope, $location, AuthService) {

    $scope.credentials = {};

    $scope.login = function() {
        AuthService.login($scope.credentials).then(function() {
            $location.path('/papers');
        }, function(response) {
            $scope.error = {
                message: response.data.error
            }
        });
    };

    $scope.register = function() {
    	$location.path('/');
    };
}]);
