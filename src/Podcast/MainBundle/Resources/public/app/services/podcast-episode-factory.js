angular.module('podcatcher')
    .factory('PodcastEpisode', function($resource) {
        return $resource(Routing.generate('get_podcasts')+'/:slug/episodes', {}, {
            query: {method: 'GET', isArray: true }
        });
    });