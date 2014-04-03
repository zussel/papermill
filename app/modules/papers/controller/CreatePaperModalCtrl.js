var app = angular.module('papermill');

app.controller('CreatePaperModalCtrl', [
    '$scope', '$http', '$modalInstance', '$fileUploader', 'Paper',
    function($scope, $http, $modalInstance, $fileUploader, Paper) {

        $scope.paper = {};

        $scope.selectPaper = function() {
            $('#PaperFileInput').click();
        };

        $scope.uploader = $fileUploader.create({
            scope: $scope,                          // to automatically update the html. Default: $rootScope
            url: 'upload.php',
            formData: [
                { key: 'value' }
            ]
        });

        $scope.uploader.bind('afteraddingfile', function(event, item) {
            $scope.paper.file = item.file;
        });

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