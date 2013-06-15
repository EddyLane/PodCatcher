'use strict';

/* Filters */

angular.module('podcatcherFilters', []).
filter('checkmark', function() {
  return function(input) {
    return input ? '\u2713' : '\u2718';
  };
}).
filter('getById', function() {
  return function(input, id) {
    var i=0, len=input.length;
    for (; i<len; i++) {
      if (+input[i].podcast_id === +id) {
        return i;
      }
    }
    return false;
  };
});