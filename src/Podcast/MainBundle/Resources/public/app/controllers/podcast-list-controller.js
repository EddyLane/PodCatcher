'use_strict';

angular.module('podcatcher')
    .controller('PodcastListCtrl', function ($scope, $http, $routeParams, limitToFilter, Category, Organization, User, Podcast) {

        $scope.routing = Routing;
        $scope.maxResultSize = 15;
        $scope.currentPage = $routeParams.page || 1;
        $scope.maxSize = $routeParams.amount || 8;
        $scope.noOfPages;
        $scope.categories = Category.query();
        $scope.organizations = Organization.query();
        $scope.sorts = [
            { name: 'A-Z', sort: 'podcast_name', direction: 'asc' },
            { name: 'Last Updated', sort: 'podcast_updated', direction: 'desc' }
        ];
        $scope.currentSort = $scope.sorts[1];
        $scope.user = User;

        var getPodcasts = function () {
            $scope.podcasts = Podcast.query({ page: $scope.currentPage, amount: $scope.maxResultSize, sort: $scope.currentSort.sort, direction: $scope.currentSort.direction, categories: $scope.selectedCategories }, function(u, getResponseHeaders){
                var headers = getResponseHeaders();
                $scope.noOfPages = Math.floor(headers["x-pagination-total"] / headers["x-pagination-amount"]) + 1;
            });
        };

        $scope.searchPodcasts = function (cityName) {
            return $http.jsonp(Routing.generate('get_podcasts', { search: cityName })).then(function (response) {
                return limitToFilter(response.data.entities, 15);
            });
        };

        $scope.setSort = function (sort) {
            $scope.currentSort = sort;
        };

        $scope.setPage = function (pageNo) {
            $scope.currentPage = pageNo;
        };

        $scope.$watch('currentPage', getPodcasts);
        $scope.$watch('currentSort', getPodcasts);
        getPodcasts();
    });