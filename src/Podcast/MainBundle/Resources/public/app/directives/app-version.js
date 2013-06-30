'use strict';

/* Directives */

angular.module('podcatcherDirectives', []).

directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
        elm.text(version);
    };
}]);