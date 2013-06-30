angular.module('podcatcher')
    .factory('Category', function($resource) {
        return $resource(Routing.generate('get_categories', {_format: 'json'}), {}, {
            query: {method: 'GET', isArray: true}
        });
    });