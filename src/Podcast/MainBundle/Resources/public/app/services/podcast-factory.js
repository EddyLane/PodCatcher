angular.module('podcatcher')
    .factory('Podcast', function($resource) {
        return $resource(Routing.generate('get_podcasts', {_format: 'json'}), {}, {
            query: { method: 'GET', isArray: true }
        });
    });