'use strict';

/* App Module */
angular.module('podcatcher', ['LocalStorageModule', 'podcatcherFilters', 'podcatcherDirectives', 'ngResource', 'ui.bootstrap']).
  config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when(Routing.generate('get_subscribed'), {templateUrl: '../partials/subscriptions.html', controller: 'SubscriptionsListCtrl'}).
      when(Routing.generate('get_podcasts'), {templateUrl: Routing.generate('get_podcasts', { _format: 'html' }), controller: 'PodcastListCtrl'}).
      when(Routing.generate('get_podcast')+'/:podcastSlug', {templateUrl: Routing.generate('get_podcast', { _format: 'html', 'slug': 'football-weekly' }), controller: 'PodcastDetailCtrl'}).
      otherwise({redirectTo: Routing.generate('get_podcasts')});
}]);
