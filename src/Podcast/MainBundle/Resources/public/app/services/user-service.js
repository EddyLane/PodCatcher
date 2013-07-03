angular.module('podcatcher')
    .service('User', function($filter, $http, localStorageService) {

        var self = this;
        this.username;
        this.email;
        this.subscriptions = [];
        this.episodes = [];   
        // this.subscriptions = JSON.parse(localStorageService.get('subscriptions')) || [];
        // this.episodes = JSON.parse(localStorageService.get('episodes')) || [];

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
                $http.post(Routing.generate('post_subscribe', { id: podcast.podcast_id }));
                // localStorageService.add('subscriptions', JSON.stringify(this.subscriptions));
            } else {
                this.subscriptions.splice(found, 1);
                // localStorageService.add('subscriptions', JSON.stringify(this.subscriptions));
            }
        }; 

        this.isSubscribed = function(podcast) {
            return ($filter('getById')(this.subscriptions, podcast.podcast_id) !== false) ? true : false;
        };

        $http.get(Routing.generate('get_user'))
        .success(function(response) {
            self.username = response.username;
            self.email = response.email;
            self.subscriptions = response.subscriptions;
        })
        .error(function(response) {
            console.log('NOT AUTHENTICATED');
        });
    });