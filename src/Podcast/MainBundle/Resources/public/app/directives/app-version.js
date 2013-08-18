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
          id: $scope.thread, 
          permalink: $window.location.href, 
          _format: 'json' 
        };
      }

      function getPostParameters() {
        return angular.extend(csrfToken, {
          'fos_comment_comment[body]': $scope.commentBody
        });
      }

      function getReplyPostParamerers(reply) {
        return angular.extend(csrfToken, {
          'fos_comment_comment[body]': reply.reply
        });
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

          $http({
            method: 'post',
            url: Routing.generate('post_thread_comments', getParameters()),
            data: $.param(getPostParameters()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).success(function(data) {
            $scope.comments.unshift({
              comment: {
                author: {
                  username: User.username
                },
                body: $scope.commentBody,
                created_at: new Date().getTime(),
                id: data.commentId,
                score: 1
              },
              children: []
            });
            $scope.commentBody = '';
            $scope.form = true;
          });
        },

        toggleReply: function(comment) {
          comment.replying = !!(!comment.replying);
        },

        reply: function(comment) {
          $http({
            method: 'post',
            url: Routing.generate('post_thread_comments', angular.extend(getParameters(), { parentId: comment.comment.id })),
            data: $.param(getReplyPostParamerers(comment)),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).success(function(data) {
           comment.children.unshift({
            comment: {
              author: {
                username: User.username
              },
              body: comment.reply,
              created_at: new Date().getTime(),
              id: data.commentId,
              score: 1
            },
            children: []
          });
           comment.reply = '';      
         });    
          comment.replying = false;
        },

        upvote: function(comment) {
          $http({
            method: 'post',
            url: Routing.generate('post_thread_comment_votes', angular.extend(getParameters(), { commentId: comment.comment.id })),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(angular.extend({ 'fos_comment_vote[_token]': csrfToken['fos_comment_comment[_token]'] }, { 'fos_comment_vote[value]': 1 }))
          });
        },

        getComments: function() {

          $http.get(Routing.generate('get_thread_comments', getParameters())).success(function(data) {
            $scope.comments = data.comments;
            setReady();
          });

        },

        getForm: function() {
          $http.get(Routing.generate('new_thread_comments', getParameters())).success(function(data) {
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