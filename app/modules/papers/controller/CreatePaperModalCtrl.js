var app = angular.module('papermill');

app.controller('CreatePaperModalCtrl', ['$scope', '$http', '$modalInstance',	function($scope, $http, $modalInstance) {

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