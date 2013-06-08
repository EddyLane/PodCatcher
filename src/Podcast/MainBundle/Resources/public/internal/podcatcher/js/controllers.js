'use strict';

/* Controllers */

function PodcastListCtrl($scope, $http) {
    
  $scope.maxResultSize = 32;
  $scope.currentPage = 1;
  $scope.maxSize = 8;
  $scope.noOfPages;
  $scope.podcastSearch = undefined;
  $scope.sorts = [
      { name: 'A-Z', sort: 'podcast_name', direction: 'asc'},
      { name: 'Last Updated', sort: 'podcast_updated', direction: 'desc'}
  ];
  
  $scope.currentSort = $scope.sorts[1];
  
  $scope.refresh = function() {
    $http.get(Routing.generate('get_podcasts', { _format: 'json', page: $scope.currentPage, amount: $scope.maxResultSize, sort: $scope.currentSort.sort, direction: $scope.currentSort.direction, categories: $scope.selectedCategories, organizations: $scope.selectedOrganizations })).success(function(data) {
      $scope.podcasts = data.entities;
      $scope.noOfPages = Math.floor(data.metadata.total / data.metadata.amount) + 1;
    });
  };
  
  $scope.setSort = function(sort) {
      $scope.currentSort = sort;
  };
  
  $scope.setPage = function(pageNo) {
    $scope.currentPage = pageNo;
  };
  
  $http.get(Routing.generate('get_categories', { _format: 'json' })).success(function(data){
      $scope.categories = data;
  });
  
  $http.get(Routing.generate('get_organizations', { _format: 'json' })).success(function(data){
      $scope.organizations = data;
  });
  
   $scope.select2Options = {
        allowClear:true
    };
  
  $scope.$watch('currentPage', $scope.refresh);
  $scope.$watch('currentSort', $scope.refresh);
  $scope.$watch('selectedCategories', $scope.refresh);
  $scope.$watch('selectedOrganizations', $scope.refresh);

  
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