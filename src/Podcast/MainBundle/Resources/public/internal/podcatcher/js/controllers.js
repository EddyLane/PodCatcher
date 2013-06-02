'use strict';

/* Controllers */

function PodcastListCtrl($scope, $http) {
  $http.get(Routing.generate('get_podcasts', { _format: 'json' })).success(function(data) {
    $scope.podcasts = data.entities;
  });

  $scope.orderProp = 'age';
}

//PodcastListCtrl.$inject = ['$scope', 'Podcast'];


//
//function PodcastDetailCtrl($scope, $routeParams, Podcast) {
//  $scope.podcast = Podcast.get({podcastId: $routeParams.podcastId}, function(podcast) {
//    $scope.mainImageUrl = podcast.images[0];
//  });
//
//  $scope.setImage = function(imageUrl) {
//    $scope.mainImageUrl = imageUrl;
//  }
//}

//PodcastDetailCtrl.$inject = ['$scope', '$routeParams', 'Podcast'];
