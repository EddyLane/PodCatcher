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
    controller: ["$scope", "$element", "$attrs", "$http", "$window", "User",
      function($scope, $element, $attrs, $http, $window, User) { 

        $scope.user = User;

        var csrfToken = {};

        function getParameters() {
          return { 
            id: 'foo', 
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

        function setReady() {
          if($scope.form && $scope.comments) {
            $scope.ready = true;
          }
        }

      	$scope.functions = {

          comment: function() {
            if(csrfToken === null) {
              throw new Error('CSRF token is null');
            }

            $scope.form = false;

            var comment = getPostParameters();

            $http({
              method: 'post',
              url: Routing.generate('fos_comment_post_thread_comments', getParameters()),
              data: $.param(comment),
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function() {
              var successComment = {
                comment: {
                  author: {
                    username: User.username
                  },
                  body: $scope.commentBody,
                  created_at: new Date().getTime()
                }
              };
              $scope.comments.unshift(successComment);
              $scope.commentBody = '';
              $scope.form = true;
            });

          },

          getComments: function() {

            $http.get(Routing.generate('fos_comment_get_thread_comments', getParameters())).success(function(data) {
              $scope.comments = data.comments;
              setReady();
            });

          },

          getForm: function() {
            $http.get(Routing.generate('fos_comment_new_thread_comments', getParameters())).success(function(data) {
              csrfToken[data.form.children._token.vars.full_name] = data.form.children._token.vars.data;
              $scope.form = true;
              setReady();
            });
          }

        };

        (function(){
          $scope.functions.getComments();
          $scope.functions.getForm();
        })();

    }]
  };
});