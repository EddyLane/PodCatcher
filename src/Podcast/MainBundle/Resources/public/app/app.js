'use strict';

/* App Module */
angular.module('podcatcher', ['LocalStorageModule', 'podcatcherFilters', 'podcatcherDirectives', 'ngResource', 'ui.bootstrap']).
  config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when(Routing.generate('get_subscribed'), {templateUrl: '../partials/subscriptions.html', controller: 'SubscriptionsListCtrl'}).
      when(Routing.generate('get_podcasts'), {templateUrl: '../partials/podcasts/get-podcasts.html', controller: 'PodcastListCtrl'}).
      when(Routing.generate('get_podcast')+'/:podcastSlug', { templateUrl: '../partials/podcasts/get-podcast.html', controller: 'PodcastDetailCtrl'}).
      otherwise({redirectTo: Routing.generate('get_podcasts')});
}]);
