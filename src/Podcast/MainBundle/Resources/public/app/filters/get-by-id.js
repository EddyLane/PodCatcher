'use strict';

/* Filters */

angular.module('podcatcherFilters', [])
    .filter('getById', function() {
        return function(input, id) {
            var i=0, len=input.length;
            for (; i<len; i++) {
                if (+input[i].id === +id) {
                    return i;
                }
            }
            return false;
        };
    })
    .filter('getFieldFromArray', function() {
        return function(field, collection) {
            var i = 0, l = collection.length, result = [];
            for(; i < l; i++) {
                result.push(collection[i][field]);
            }
            return result;
        };
    })
    .filter('orderByField', function() {
        return function(field, collection) {
            return collection.sort(function(a, b) {
                return a[field] - b[field];
            });
        }
    });