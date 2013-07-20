angular.module('podcatcher')
    .factory('Episode', function($resource) {
        return $resource(Routing.generate('get_episodes'), {}, {
            query: {method: 'GET', isArray: true }
        });
    });