var app = angular.module('papermill');

app.controller('SigninCtrl', ['$scope', '$location', 'AuthService', function($scope, $location, AuthService) {

    $scope.error = {};

    $scope.profile = {};

    $scope.signin = function() {
        delete $scope.error.message;
        var parsed = NameParse.parse($scope.profile.profile.name);
        $scope.profile.first_name = parsed.firstName;
        $scope.profile.last_name = parsed.lastName;
        AuthService.signin($scope.profile).success(function() {
            $location.path('/confirm');
        }).error(function(data) {
            $scope.error.message = data.error;
        });
    }
}]);
