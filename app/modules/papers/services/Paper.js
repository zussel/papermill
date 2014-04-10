'use strict';

angular.module('papermill').factory("Paper", function ($resource) {
    return $resource("/api/paper/:id", { id: "@id" }, {
        "update": {method: "PUT"}
    });
});
