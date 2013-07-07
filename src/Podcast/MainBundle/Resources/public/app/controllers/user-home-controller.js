'use strict';

angular.module('podcatcher')
    .controller('UserHomeCtrl', function ($scope, Podcast) {
        $scope.carouselPodcasts = Podcast.query({ amount: 5 });
    });
