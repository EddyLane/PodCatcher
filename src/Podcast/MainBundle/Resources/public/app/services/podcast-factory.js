angular.module('podcatcher')
    .factory('Podcast', function($resource) {
        return $resource(Routing.generate('get_podcasts')+'/:slug', {}, {
            query:  { method: 'GET', isArray: true, headers: [{'Content-Type': 'application/json'}, {'Accept': 'application/json'}]},
            get:    { method: 'GET', headers: [{'Content-Type': 'application/json'}, {'Accept': 'application/json'}]}
        });
    });