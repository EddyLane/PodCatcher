'use strict';

angular.module('podcatcher')
    .controller('SubscriptionsListCtrl', function ($routeParams, $filter, $scope, $http, User, PodcastEpisode, Podcast) {
        $scope.user = User;

        $scope.showSubscription = function(subscription) {
            $scope.loading = true;
            $scope.podcast = subscription;
            $scope.podcast.episodes = PodcastEpisode.query({ slug: subscription.slug }, function() {
                $scope.loading = false;
            });
        };

        if($routeParams.slug) {
            $scope.loading = true;
            Podcast.get({ slug: $routeParams.slug }, $scope.showSubscription)
        } else if(User.subscriptions.length > 0) {
            return ($filter('getById')(this.subscriptions, podcast.id) !== false) ? true : false;
            $scope.showSubscription(User.subscriptions[0]);
        }

    });