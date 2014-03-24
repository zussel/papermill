var app = angular.module('papermill');

app.controller('CreatePaperModalCtrl', ['$scope', '$modalInstance',	function($scope, $modalInstance) {
	$scope.ok = function () {
		$modalInstance.close();
	};

	$scope.cancel = function () {
    	$modalInstance.dismiss('cancel');
  	};
}]);