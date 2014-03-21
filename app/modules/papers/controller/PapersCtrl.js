var app = angular.module('papermill');

app.controller('PapersCtrl', ['$scope', function($scope) {
    $scope.papers = [{
        title: 'Paper 1',
        author: 'Gabi Kühl',
        published: {
            by: 'PaperPress',
            year: 2011
        },
        type: 'PDF',
        tags: ['Paläontolgy', 'Fossils', 'Kambrium']
    }, {
        title: 'Paper 2',
        author: 'Jes Rust',
        published: {
            by: 'PaperPress',
            year: 2009
        },
        type: 'PDF',
        tags: ['Paläontolgy', 'Invertebraten', 'Devon']
    }, {
        title: 'Paper 3',
        author: 'Gabi Kühl',
        published: {
            by: 'PaperPress',
            year: 2005
        },
        type: 'PDF',
        tags: ['Paläontolgy', 'Hunsrück', 'Schinderhannes', 'Fossils']
    }];
}]);