'use strict';

/* Controllers */

function SubscriptionsListCtrl($scope, User) {
    $scope.user = User;
}

function LoginFormCtrl($scope, User) {
    
    $scope.user = User;
    
    $scope.login = function(userForm) {
        console.log(Routing.generate("fos_user_security_check"));
    };
    
    $scope.open = function() {
        $scope.shouldBeOpen = true;
    };

    $scope.close = function() {
        $scope.closeMsg = 'I was closed at: ' + new Date();
        $scope.shouldBeOpen = false;
    };

    $scope.options = {
        backdropFade: true,
        dialogFade: true
    };
}

function PodcastListCtrl($scope, $http, limitToFilter, Category, Organization, User) {
  $scope.maxResultSize = 32;
  $scope.currentPage = 1;
  $scope.maxSize = 8;
  $scope.noOfPages;
  $scope.categories = Category.query();
  $scope.organizations = Organization.query();
  $scope.sorts = [
      { name: 'A-Z', sort: 'podcast_name', direction: 'asc'},
      { name: 'Last Updated', sort: 'podcast_updated', direction: 'desc'}
  ];
  $scope.currentSort = $scope.sorts[1];
  $scope.user = User;
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
        

//                setTimeout(function(){
//              $("#thumb-view").isotope({ 
//                    itemSelector: '.view',
//                    masonry: {
//                        columnWidth: 5
//                    }
//                });
//                },3000);
    });
  };
    
  $scope.searchPodcasts = function(cityName) {
    return $http.jsonp(Routing.generate('get_podcasts', { search: cityName })).then(function(response){
      return limitToFilter(response.data.entities, 15);
    });
  };
  
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