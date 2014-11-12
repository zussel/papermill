'use strict';

angular.module('papermill').factory("Authors", function ($resource) {
    return $resource("/api/profile/:id", { id: "@id" }, {
        "update": {
            method: "PUT"
        },
        "find": {
            method: "GET",
            url: '/api/profile/find',
            isArray: true
        }
    });
});
