angular.module('podcatcher')
    .factory('Organization', function($resource) {
        return $resource(Routing.generate('get_organizations', {_format: 'json'}), {}, {
            query: { method: 'GET', isArray: true }
        });
    });