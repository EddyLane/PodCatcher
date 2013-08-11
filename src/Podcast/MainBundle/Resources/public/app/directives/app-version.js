'use strict';

/* Directives */

angular.module('podcatcherDirectives', []).
directive('comments', function() {
  return {
    templateUrl: '/partials/comments.html',
    restrict: "A",
    scope: {
        thread: "=thread"
    },
    controller: ["$scope", "$element", "$attrs", "$http", "$window",
      function($scope, $element, $attrs, $http, $window) { 
      	$http.get(Routing.generate('fos_comment_get_thread_comments', { id: 'foo', permalink: $window.location.href, _format: 'json' })).success(function(data) {
          $scope.comments = data.comments;
        })
    }]
  };
});