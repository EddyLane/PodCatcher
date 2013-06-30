'use strict';

angular.module('podcatcher')
    .controller('SubscriptionsListCtrl', function ($scope, $http, $filter, User) {
        $scope.user = User;
        $scope.setActive = function (podcast) {
            $scope.active = podcast;
            $http.get(Routing.generate('get_subscription', { _format: 'json', slug: podcast.podcast_slug })).success(function (data) {
                $scope.active.episodes = data;
            });
        };
    });