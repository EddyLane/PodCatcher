'use strict';

angular.module('podcatcher')
    .controller('PodcastDetailCtrl', function ($scope, $routeParams, Podcast, Episode) {
        $scope.podcast = Podcast.get({ slug: $routeParams.podcastSlug }, function() {
            $scope.podcast.episodes = Episode.query({ slug: $routeParams.podcastSlug });
        });
    });
