'use strict';

/* App Module */
console.log(Routing.generate('get_podcast'));
console.log(Routing.generate('get_podcasts'));

angular.module('podcatcher', ['podcatcherFilters']).
  config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when(Routing.generate('get_podcasts'), {templateUrl: Routing.generate('get_podcasts', { _format: 'html' }), controller: PodcastListCtrl}).
      when(Routing.generate('get_podcast')+'/:podcastSlug', {templateUrl: Routing.generate('get_podcast', { _format: 'html', 'slug': 'football-weekly' }), controller: PodcastDetailCtrl}).
      otherwise({redirectTo: Routing.generate('get_podcasts')});
}]);
