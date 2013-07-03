angular.module('podcatcher')
    .service('User', function($filter, $http, localStorageService) {

        this.username;
        this.email;
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

        $http.get(Routing.generate('get_user', { format: 'json' })).success(function(response) {
            console.log(response);
        });

    });