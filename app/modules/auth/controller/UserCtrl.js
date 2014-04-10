/**
 * Created by sascha on 10.04.14.
 */

var app = angular.module('papermill');

app.controller('UserCtrl', ['$scope', '$location', function($scope, $location) {
    $scope.$root.$on("$routeChangeError", function () {

        console.log("failed to change routes");
        $location.path('/login');
    });
}]);