'use strict';

angular.module('podcatcher')
    .controller('SubscriptionsListCtrl', function ($scope, $http, $filter, User, Episode) {
        $scope.user = User;
        $scope.showSubscription = function(subscription) {
            $scope.podcast = subscription;
            $scope.podcast.episodes = Episode.query({ slug: subscription.slug });
        }
    });