var app = angular.module('papermill');

app.controller('SigninCtrl', ['$scope', 'AuthService', function($scope, AuthService) {

    $scope.profile = {};

    $scope.signin = function() {
        var parsed = NameParse.parse($scope.profile.name);
        $scope.profile.first_name = parsed.firstName;
        $scope.profile.last_name = parsed.lastName;
        AuthService.signin($scope.profile);
    }
}]);
