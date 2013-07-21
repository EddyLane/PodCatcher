'use strict';

angular.module('podcatcher')
    .controller('SubscriptionsListCtrl', function ($scope, $http, User, PodcastEpisode) {
        $scope.user = User;
        $scope.showSubscription = function(subscription) {
            $scope.podcast = subscription;
            $scope.podcast.episodes = PodcastEpisode.query({ slug: subscription.slug });
        }
    });