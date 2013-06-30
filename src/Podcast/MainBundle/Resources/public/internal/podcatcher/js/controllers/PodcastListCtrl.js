'use_strict';

angular.module('podcatcher')
    .controller('PodcastListCtrl', function ($scope, $http, $routeParams, limitToFilter, Category, Organization, User) {
        $scope.maxResultSize = 15;
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
        $scope.refresh = function () {
            return getPodcasts();
            location.href = "#/" + Routing.generate('get_podcasts', { page: $scope.currentPage, categories: $scope.selectedCategories, organizations: $scope.selectedOrganizations });
        };

        var getPodcasts = function () {
            $http({
                url: Routing.generate('get_podcasts', { _format: 'json', categories: $scope.selectedCategories, organizations: $scope.selectedOrganizations }),
                method: 'GET',
                params: {
                    page: $scope.currentPage,
                    amount: $scope.maxResultSize,
                    sort: $scope.currentSort.sort,
                    direction: $scope.currentSort.direction
                }
            }).success(function (response) {
                    $scope.podcasts = response.entities;
                    $scope.noOfPages = Math.floor(response.metadata.total / response.metadata.amount) + 1;


                    setTimeout(function () {
                        $("#thumb-view").isotope({
                            itemSelector: '.view',
                            containerStyle: { position: 'relative', overflow: 'visible' },
                            masonry: {
                                columnWidth: 5
                            }
                        }, function () {

                            console.log($('#thumb-view').width());
                            $('.podcast-order').width($('#thumb-view').width());
                        })
                    }, 10);
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

        //$scope.refresh();
        $scope.$watch('currentPage', $scope.refresh);
        $scope.$watch('currentSort', $scope.refresh);
        $scope.$watch('selectedCategories', $scope.refresh);
        $scope.$watch('selectedOrganizations', $scope.refresh);
    });