'use strict';

angular.module('podcatcher')

    .controller('UserHomeCtrl', function ($scope, Podcast, Episode, User) {
        $scope.pubDate = new Date();
        $scope.maxDate = new Date();
        $scope.user = User;
        $scope.latestEpisodes = Episode.query();

        $scope.isSubscribed = function(episode) {
            return User.isSubscribed({ id: episode.podcastId });
        };

        var refresh = function() {
            Episode.query({
                "pub_date": moment($scope.pubDate).format('YYYY-MM-DD'),
                "podcast": ($scope.type === 'subscribed') ? User.getSubscriptionIds() : []
            }, function(response) {
                $scope.episodes = response;
            });
        };

        $scope.$watch('pubDate', refresh);
        $scope.$watch('type', refresh);
    });
