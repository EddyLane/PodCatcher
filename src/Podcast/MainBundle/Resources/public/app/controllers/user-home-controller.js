'use strict';

angular.module('podcatcher')

    .controller('UserHomeCtrl', function ($scope, Podcast, Episode) {
        $scope.$watch('pubDate', function(newDate, oldDate) {
            console.log(newDate);
            if(newDate instanceof Date) {
                $scope.episodes = Episode.query({ pub_date: moment(newDate).format('YYYY-MM-DD') });
            }
        });
    });
