'use strict';

angular.module('podcatcher')
    .controller('PodcastDetailCtrl', function ($scope, $routeParams, Podcast, Episode) {
        $scope.currentPage = $routeParams.page || 1;
        $scope.maxSize = $routeParams.amount || 8;
        $scope.noOfPages;
        $scope.podcast = Podcast.get({ slug: $routeParams.podcastSlug }, function() {
            $scope.podcast.episodes = Episode.query({ slug: $routeParams.podcastSlug }, function(u, getResponseHeaders) {
                var headers = getResponseHeaders();
                $scope.noOfPages = Math.floor(headers["x-pagination-total"] / headers["x-pagination-amount"]) + 1;
            });
        });
    });
