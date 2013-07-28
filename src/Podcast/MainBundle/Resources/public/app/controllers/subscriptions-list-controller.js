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

        $scope.subscriptionFilter = function(episode) {
            return ($scope.onlyNew) ? User.episodes.indexOf(episode.id) === -1 : true;
        }

        $http.get(Routing.generate('get_episodes_new'), { podcast: User.getSubscriptionIds() });

        if($routeParams.slug) {
            $scope.loading = true;
            Podcast.get({ slug: $routeParams.slug }, $scope.showSubscription)
        } else if(User.subscriptions.length > 0) {
             $scope.showSubscription(User.subscriptions[0]);
        }

    });