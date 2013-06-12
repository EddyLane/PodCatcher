'use strict';

angular.module('podcatcherServices', ['ngResource'])
.factory('Category', function($resource) {
    return $resource(Routing.generate('get_categories', {_format: 'json'}), {}, {
        query: {method: 'GET', isArray: true}
    });
})
.factory('Organization', function($resource) {
    return $resource(Routing.generate('get_organizations', {_format: 'json'}), {}, {
        query: {method: 'GET', isArray: true}
    });
})
.service('User', function() {
    this.subscriptions = [];
    this.subscribe = function(podcast) {
        if(this.subscriptions.indexOf(podcast) === -1) {
            this.subscriptions.push(podcast);
            console.log(this.subscriptions);
        }
    };
});
