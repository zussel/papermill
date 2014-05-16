var app = angular.module('papermill');

app.controller('CreatePaperModalCtrl', [
    '$scope', '$http', '$modalInstance', '$upload', 'Paper',
    function($scope, $http, $modalInstance, $upload, Paper) {

        $scope.paper = {};

        $scope.selectPaper = function() {
            $('#PaperFileInput').click();
        };

        $scope.onPaperSelect = function($files) {
            $scope.paper.file = $files[0];
        };

        $scope.ok = function () {

            /*
             * 1. init progressbar
             * 2. upload paper
             * 3. save paper data
             * 4. validate response
             */
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