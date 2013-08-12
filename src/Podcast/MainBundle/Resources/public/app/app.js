'use strict';

/* App Module */
angular.module('podcatcher', ['podcatcherFilters', 'podcatcherDirectives' ,'ngResource', 'ui.bootstrap', 'angularMoment']).
  config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when(Routing.generate('get_subscribed'), {templateUrl: '../partials/subscriptions.html', controller: 'SubscriptionsListCtrl'}).
      when(Routing.generate('get_subscription')+'/:slug', {templateUrl: '../partials/subscriptions.html', controller: 'SubscriptionsListCtrl'}).
      when(Routing.generate('get_podcasts'), {templateUrl: '../partials/podcasts/get-podcasts.html', controller: 'PodcastListCtrl'}).
      when(Routing.generate('get_podcast')+'/:slug', { templateUrl: '../partials/podcasts/get-podcast.html', controller: 'PodcastDetailCtrl'}).
      when(Routing.generate('get_home'), { templateUrl: '../partials/user/home.html', controller: 'UserHomeCtrl' }).
      otherwise({redirectTo: Routing.generate('get_home')});
}]);
