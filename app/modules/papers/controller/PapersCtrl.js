var app = angular.module('papermill');

app.controller('PapersCtrl', [
    '$scope', '$modal', 'Paper', '$log',
    function($scope, $modal, Paper, $log) {


        Paper.query(function(response) {
            $scope.papers = response;
        }, function() {
            console.log("failure");
        });

        $scope.create = function() {
            var modalInstance = $modal.open({
                templateUrl: 'app/modules/papers/partials/create-paper-modal.html',
                controller: 'CreatePaperModalCtrl'
            });

            modalInstance.result.then(function () {
                $log.info('Modal accepted at: ' + new Date())
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };
    }
]);
