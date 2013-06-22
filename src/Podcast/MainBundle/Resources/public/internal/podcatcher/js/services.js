'use strict';

angular.module('podcatcherServices', ['ngResource', 'podcatcherFilters', 'LocalStorageModule'])

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

.service('User', function($filter, localStorageService) {

    this.username = localStorageService.get('username');
    this.email = localStorageService.get('email');
    this.subscriptions = JSON.parse(localStorageService.get('subscriptions')) || [];

    this.authenticate = function(details) {
        
    };

    this.persist = function() {
        localStorageService.add('username', this.username);
        localStorageService.add('email', this.email);
    };

    this.subscribe = function(podcast) {
        var found = $filter('getById')(this.subscriptions, podcast.podcast_id);
        if(found === false) {
            this.subscriptions.push(podcast);
            localStorageService.add('subscriptions', JSON.stringify(this.subscriptions));
        } else {
            this.subscriptions.splice(found, 1);
            localStorageService.add('subscriptions', JSON.stringify(this.subscriptions));
        }
    };

    this.isSubscribed = function(podcast) {
        return ($filter('getById')(this.subscriptions, podcast.podcast_id) !== false) ? true : false;
    };
});