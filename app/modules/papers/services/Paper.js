angular.module('papermill').service.factory("Paper", function ($resource) {
    return $resource(
        "/api/paper/:id",
        {
        	id: "@id"
        }, {
            "update": {method: "PUT"}
        }
    );
});