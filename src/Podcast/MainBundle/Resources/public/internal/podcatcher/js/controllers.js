'use strict';

/* Controllers */

function PodcastListCtrl($scope, $http) {
  $http.get(Routing.generate('get_podcasts', { _format: 'json' })).success(function(data) {
    $scope.podcasts = data.entities;
  });
  $scope.orderProp = 'age';
}

//PodcastListCtrl.$inject = ['$scope', 'Podcast'];



function PodcastDetailCtrl($scope, $routeParams, $http) {
  $http.get(Routing.generate('get_podcast', { _format: 'json', slug: $routeParams.podcastSlug })).success(function(data) {
    $scope.podcast = data;
    $http.get(Routing.generate('get_podcast_episodes', { _format: 'json', slug: $routeParams.podcastSlug })).success(function(data) {
        $scope.podcast.episodes = data;
    });
  });
  
}

//PodcastDetailCtrl.$inject = ['$scope', '$routeParams', 'Podcast'];
