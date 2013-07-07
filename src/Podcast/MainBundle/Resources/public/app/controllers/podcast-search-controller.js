'use strict';

angular.module('podcatcher')
    .controller('PodcastSearchCtrl', function ($scope, $http, limitToFilter, Podcast, User) {
        $scope.search = function(term) {
            return $http.get(Routing.generate('get_podcasts', { search: term, limit: 10 })).then(function(response){
                return limitToFilter(response.data, 10);
            });
        };
    });
