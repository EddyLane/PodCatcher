'use strict';

angular.module('podcatcher')

    .controller('UserHomeCtrl', function ($scope, Podcast, Episode, User) {

        $scope.pubDate = new Date();
        $scope.maxDate = new Date();
        $scope.routing = Routing;
        $scope.user = User;
        $scope.loadingLatest = true;

        Episode.query(function(result) {
            $scope.latestEpisodes = result;
            $scope.loadingLatest = false;
        });

        $scope.carouselPodcasts = Podcast.query({ amount: 5 });

        $scope.isSubscribed = function(episode) {
            return User.isSubscribed({ id: episode.podcastId });
        };

        function refresh() {
            $scope.loading = true;
            Episode.query({ "pub_date": moment($scope.pubDate).format('YYYY-MM-DD'),
                "podcast": ($scope.type === 'subscribed') ? User.getSubscriptionIds() : []
            }, function(result) {
                $scope.episodes = result;
                $scope.loading = false;
            });
        };

        $scope.$watch('pubDate', refresh);
        $scope.$watch('type', refresh);
        
    });