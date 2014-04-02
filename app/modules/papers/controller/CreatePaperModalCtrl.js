var app = angular.module('papermill');

app.controller('CreatePaperModalCtrl', [
    '$scope', '$http', '$modalInstance', 'Paper',
    function($scope, $http, $modalInstance, Paper) {

        $scope.paper = {};

        $scope.ok = function () {

            Paper.save({}, $scope.paper, function() {
                console.log('success');
            }, function() {
                console.log('failure');
            });
            $modalInstance.close();
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    }
]);