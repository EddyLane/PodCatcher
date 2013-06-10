'use strict';

/* Controllers */



function PodcastListCtrl($scope, $http, Category, Organization) {
    
  $scope.maxResultSize = 32;
  $scope.currentPage = 1;
  $scope.maxSize = 8;
  $scope.noOfPages;
  $scope.podcastSearch = undefined;
  $scope.categories = Category.query();
  $scope.organizations = Organization.query();
  $scope.sorts = [
      { name: 'A-Z', sort: 'podcast_name', direction: 'asc'},
      { name: 'Last Updated', sort: 'podcast_updated', direction: 'desc'}
  ];
  $scope.currentSort = $scope.sorts[1];

    

  $scope.refresh = function() {
    $http({
        url: Routing.generate('get_podcasts', { _format: 'json', categories: $scope.selectedCategories, organizations: $scope.selectedOrganizations }),
        method: 'GET',
        params: {
            page: $scope.currentPage,
            amount: $scope.maxResultSize,
            sort: $scope.currentSort.sort,
            direction: $scope.currentSort.direction
        }
    }).success(function(response) {
        $scope.podcasts = response.entities;
        $scope.noOfPages = Math.floor(response.metadata.total / response.metadata.amount) + 1;
    });
  };
  
  $scope.searchPodcasts = function(query) {
  return [
      { podcast_name: 'Test' }
  ];
  return ['One', 'Two', 'Three'];
    return $http.jsonp(Routing.generate('get_podcasts', { _format: 'json', search: query, amount: 15 })).then(function(response){
        return response.entities;
    });
  }
  
  $scope.setSort = function(sort) {
      $scope.currentSort = sort;
  };
  
  $scope.setPage = function(pageNo) {
    $scope.currentPage = pageNo;
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