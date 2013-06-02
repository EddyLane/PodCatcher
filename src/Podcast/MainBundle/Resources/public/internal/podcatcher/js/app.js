'use strict';

/* App Module */

angular.module('podcatcher', ['podcatcherFilters']).
  config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when(Routing.generate('get_podcasts'), {templateUrl: Routing.generate('get_podcasts', { _format: 'html' }),   controller: PodcastListCtrl}).
      //when('/phones/:phoneId', {templateUrl: 'partials/phone-detail.html', controller: PhoneDetailCtrl}).
      otherwise({redirectTo: Routing.generate('get_podcasts')});
}]);
