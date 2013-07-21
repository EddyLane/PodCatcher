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
            $scope.loading = true;
            Podcast.query({ page: $scope.currentPage, amount: $scope.maxResultSize, sort: $scope.currentSort.sort, direction: $scope.currentSort.direction, categories: $scope.selectedCategories }, function(result, getResponseHeaders){
                var headers = getResponseHeaders();
                $scope.podcasts = result;
                $scope.noOfPages = Math.floor(headers["x-pagination-total"] / headers["x-pagination-amount"]) + 1;
                $scope.loading = false;
            });
        };

        $scope.setSort = function (sort) {
            $scope.currentSort = sort;
            getPodcasts();
        };

        $scope.setPage = function (pageNo) {
            $scope.currentPage = pageNo;
            getPodcasts();
        };


        getPodcasts();

        $scope.$watch('currentPage', getPodcasts);
    });