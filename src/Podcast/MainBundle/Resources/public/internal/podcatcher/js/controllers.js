'use strict';

/* Controllers */

function PodcastListCtrl($scope, $http) {
  
  $scope.refresh = function() {
    $http.get(Routing.generate('get_podcasts', { _format: 'json', page: $scope.currentPage, amount: $scope.maxResultSize })).success(function(data) {
      $scope.podcasts = data.entities;
      $scope.noOfPages = Math.floor(data.metadata.total / data.metadata.amount) + 1;
    });
  };
  $scope.setPage = function(pageNo) {
    $scope.currentPage = pageNo;
  };
  $scope.$watch('currentPage', $scope.refresh);
  
  $scope.noOfPages = 102;
  $scope.maxResultSize = 32;
  $scope.currentPage = 1;
  $scope.maxSize = 5;
  
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