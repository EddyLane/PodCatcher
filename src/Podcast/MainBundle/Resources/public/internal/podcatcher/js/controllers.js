'use strict';

/* Controllers */

function HomeCtrl($scope, $http) {
}

function SubscriptionsListCtrl($scope, $http, $filter, User) {
    $scope.user = User;

    $scope.setActive = function(podcast) {
        $scope.active = podcast;
        $http.get(Routing.generate('get_subscription', { _format: 'json', slug: podcast.podcast_slug })).success(function(data) {
            $scope.active.episodes = data;
        });
    };
}

function SecurityCtrl($http, $scope, User) {
    
    $scope.copy = User;
    $scope.master= User;

    $scope.register= function(userForm) {

        $http({
                method: 'POST',
          url: Routing.generate('register'),
          data: $('#registration-form').serialize(),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })        
        .success(function(data, status, headers, config) {
             console.log(data);
        }).error(function(data, status, headers, config) {
             console.log(status);
             console.log(data);
        });


        $scope.master = angular.copy($scope.copy);
        $scope.master.persist();
    };

    $scope.options = {
        backdropFade: true,
        dialogFade: true
    };
 
  $scope.reset = function() {
    $scope.copy = angular.copy($scope.master);
  };
 
  $scope.reset();    
}

function PodcastListCtrl($scope, $http, $routeParams, limitToFilter, Category, Organization, User) {
  console.log($routeParams);
  $scope.maxResultSize = 32;
  $scope.currentPage = $routeParams.page || 1;
  $scope.maxSize = $routeParams.amount || 8;
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
    return getPodcasts();
    location.href = "#/"+Routing.generate('get_podcasts', { page: $scope.currentPage, categories: $scope.selectedCategories, organizations: $scope.selectedOrganizations });
  };

  var getPodcasts = function() {
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
        

               setTimeout(function(){
             $("#thumb-view").isotope({ 
                   itemSelector: '.view',
                   containerStyle: { position: 'relative', overflow: 'visible' },
                   masonry: {
                       columnWidth: 5
                   }
               })
               },3000);
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

  //$scope.refresh();
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