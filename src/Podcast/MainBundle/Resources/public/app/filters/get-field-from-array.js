'use strict';

/* Filters */

angular.module('podcatcherFilters', []).
    filter('getFieldFromArray', function() {
        return function(field, collection) {
            var i = 0, l = collection.length, result = [];
            for(; i < l; i++) {
                result.push(collection[i][field]);
            }
            return result;
        };
    });