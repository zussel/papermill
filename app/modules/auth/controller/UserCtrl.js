/**
 * Created by sascha on 10.04.14.
 */

var app = angular.module('papermill');

app.controller('UserCtrl', ['$scope', '$location', 'AuthService', function($scope, $location, AuthService) {
    $scope.$root.$on("$routeChangeError", function () {

        console.log("failed to change routes");
        $location.path('/login');
    });

    $scope.isActive = function(path) {
    	return $location.path().substr(0, path.length) === path;
    };

    $scope.loggedIn = function() {
        return AuthService.loggedIn();
    };

    $scope.logout = function() {
        AuthService.logout();
    };

    $scope.user = function() {
        return AuthService.user;
    };
}]);