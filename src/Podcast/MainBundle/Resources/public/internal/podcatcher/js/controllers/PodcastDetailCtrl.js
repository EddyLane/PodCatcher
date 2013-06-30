'use strict';

angular.module('podcatcher')
    .controller('PodcastDetailCtrl', function ($scope, $routeParams, $http) {
        $http.get(Routing.generate('get_podcast', { _format: 'json', slug: $routeParams.podcastSlug })).success(function (data) {
            $scope.podcast = data;
            $http.get(Routing.generate('get_podcast_episodes', { _format: 'json', slug: $routeParams.podcastSlug })).success(function (data) {
                $scope.podcast.episodes = data;
            });
        });
    });
