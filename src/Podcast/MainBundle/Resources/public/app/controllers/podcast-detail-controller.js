'use strict';

angular.module('podcatcher')
    .controller('PodcastDetailCtrl', function ($scope, $routeParams, Podcast, PodcastEpisode, User) {
        $scope.currentPage = $routeParams.page || 1;
        $scope.maxSize = $routeParams.amount || 8;
        $scope.user = User;
        $scope.noOfPages;
        $scope.loadingPodcast = true;
        $scope.loadingEpisodes = true;
        Podcast.get({ slug: $routeParams.slug }, function(data) {
            $scope.loadingPodcast = false;
            $scope.podcast = data;
            $scope.podcast.episodes = PodcastEpisode.query({ slug: $routeParams.slug }, function(u, headers) {
                $scope.loadingEpisodes = false;
                $scope.noOfPages = Math.floor(headers()["x-pagination-total"] / headers()["x-pagination-amount"]) + 1;
            });
        });
    });
