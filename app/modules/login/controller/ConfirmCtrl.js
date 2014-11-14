var app = angular.module('papermill');

app.controller('ConfirmCtrl', ['$scope', '$location', '$interval', function($scope, $location, $interval) {
    $interval(function() {
        $location.path('/login');
    }, 4000, 1);
}]);
