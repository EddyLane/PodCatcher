'use strict';

angular.module('podcatcher')
    .controller('PodcastDetailCtrl', function ($scope, $routeParams, Podcast, PodcastEpisode, User) {
        $scope.currentPage = $routeParams.page || 1;
        $scope.maxSize = $routeParams.amount || 8;
        $scope.user = User;
        $scope.noOfPages;
        $scope.loadingPodcast = true;
        $scope.loadingEpisodes = true;

        var commentsOn = false;
        $scope.toggleComments = function(episode) {
            if(commentsOn) {
                commentsOn.episodes = false;
            }
            commentsOn = episode;
            episode.comments = !(episode.comments != 0);
        }

        $scope.podcast = Podcast.get({ slug: $routeParams.slug }, function(data) {
            $scope.loadingPodcast = false;
            $scope.podcast.episodes = PodcastEpisode.query({ slug: $routeParams.slug }, function(u, headers) {
                $scope.loadingEpisodes = false;
                $scope.noOfPages = Math.floor(headers()["x-pagination-total"] / headers()["x-pagination-amount"]) + 1;
            });
        });
    });
