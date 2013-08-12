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

        var csrfToken = {};

        function getParameters() {
          return { 
            id: $scope.thread, 
            permalink: $window.location.href, 
            _format: 'json' 
          };
        }

        function getPostParameters() {
          var body = {
            'fos_comment_comment[body]': $scope.commentBody
          };
          console.log(angular.extend(csrfToken, body));
          return angular.extend(csrfToken, body);
        }



      	$scope.functions = {

          comment: function() {
            if(csrfToken === null) {
              throw new Exception('CSRF token is null');
            }
            $http({
              method: 'post',
              url: Routing.generate('fos_comment_post_thread_comments', getParameters()),
              data: $.param(getPostParameters()),
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            });
          },

          getComments: function() {
            $http.get(Routing.generate('fos_comment_get_thread_comments', getParameters())).success(function(data) {
              $scope.comments = data.comments;
            });
          },

          getForm: function() {
            $http.get(Routing.generate('fos_comment_new_thread_comments', getParameters())).success(function(data) {
              csrfToken[data.form.children._token.vars.full_name] = data.form.children._token.vars.data;
            });
          }

        };

        (function(){
          $scope.functions.getComments();
          $scope.functions.getForm();
        })()

    }]
  };
});