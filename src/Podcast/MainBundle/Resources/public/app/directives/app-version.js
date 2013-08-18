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
            // id: $scope.thread, 
            id: 'foo', 
            permalink: $window.location.href, 
            _format: 'json' 
          };
        }

        function getPostParameters() {
          return angular.extend(csrfToken, {
            'fos_comment_comment[body]': $scope.commentBody
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
              url: Routing.generate('fos_comment_post_thread_comments', getParameters()),
              data: $.param(getPostParameters()),
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function() {
              $scope.comments.unshift({
                comment: {
                  author: {
                    username: User.username
                  },
                  body: $scope.commentBody,
                  created_at: new Date().getTime()
                }
              });
              $scope.commentBody = '';
              $scope.form = true;
            });
          },

          reply: function(comment) {

            comment.replying = !!(!comment.replying);

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
})
.directive('test', function($compile) {
  // use an int to suffix scope properties 
  // so that inheritance does not cause infinite loops anymore
  var inc = 0;
  return {
    restrict: 'A',
    compile: function(element, attr) {
      // prepare property names
      var prop = 'test'+(++inc),
          childrenProp = 'children_'+prop,
          labelProp = 'label'+prop,
          childProp = 'child_'+prop;
      
      return function(scope, element, attr) {
        // create a child scope
        var childScope = scope.$new();
        function observeParams() {
          // eval attributes in current scope
          // and generate html depending on the type
          // console.log(childProp);
          // console.log(childrenProp);
          // console.log(labelProp);

          console.log(attr);

          var iTest = scope.$eval(attr.test),
              iLabel = scope.$eval(attr.testLabel);
        
            if(iTest[0] && iTest[0] && iTest[0].comment) {
              labelProp = iTest[0].comment.body;
            }

          var html = 
              '<div>'+labelProp+'<ul><li ng-repeat="'+childProp+' in '+childrenProp+'"><div test="'+childProp+'.children" test-label="'+childProp+'.label">{{'+childProp+'}}</div></li></ul></div>'
            ;


          //  console.log(iTest);
          
          // set scope values and references
          childScope[childrenProp]= iTest;
          childScope[labelProp]= iLabel;
          
          // fill html
          element.html(html);
          
          // compile the new content againts child scope
          $compile(element.contents())(childScope);
        }
        
        // set watchers
        scope.$watch(attr.test, observeParams);
        scope.$watch(attr.testLabel, observeParams);
      };
    }
  };
});


