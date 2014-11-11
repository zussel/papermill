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

        $scope.refreshAuthors = function(term) {
            var params = {term: term};
            return $http.get(
                '/api/author',
                {params: params}
            ).then(function(response) {
                $scope.authors = response.data.results;
            });
        };

        $scope.ok = function () {

            /*
             * 1. init progressbar
             * 2. upload paper
             * 3. save paper data
             * 4. validate response
             */
            $scope.upload = $upload.upload({
                url: '/api/paper',
                method: 'POST',
                data: {
                    paper: $scope.paper
                },
                file: $scope.paper.file
            }).then(function(response) {
                console.log('successfully saved paper:');
                console.log(response.data);
//                $scope.uploadResult.push(response.data);
            }, function(response) {
                if (response.status > 0) $scope.errorMsg = response.status + ': ' + response.data;
            }, function(evt) {
                // Math.min is to fix IE which reports 200% sometimes
                $scope.progress[index] = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
            }).xhr(function(xhr){
                xhr.upload.addEventListener('abort', function() {console.log('abort complete')}, false);
            });
/*            Paper.save({}, $scope.paper, function() {
                console.log('success');
            }, function() {
                console.log('failure');
            });*/
//            $modalInstance.close();
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    }
]);