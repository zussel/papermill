var app = angular.module('papermill');

app.controller('CreatePaperModalCtrl', ['$scope', '$modalInstance',	function($scope, $modalInstance) {

	$scope.paper = {
		title: '',
		author: '',
		year: 2014
	};

	$scope.ok = function () {
		$modalInstance.close();
	};

	$scope.cancel = function () {
    	$modalInstance.dismiss('cancel');
  	};
}]);