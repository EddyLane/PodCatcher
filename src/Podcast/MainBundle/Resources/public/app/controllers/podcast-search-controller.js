'use strict';

angular.module('podcatcher')
    .controller('PodcastSearchCtrl', function ($scope, $http, $location) {

        $scope.search = function(term) {
            return $http.get(Routing.generate('get_podcasts', { search: term, limit: 10 })).then(function(response){
                return response.data;
            });
        };

        $scope.onSelect = function($item) {
            $location.path(Routing.generate('get_podcast', { slug: $item.slug }));
        }

    });
