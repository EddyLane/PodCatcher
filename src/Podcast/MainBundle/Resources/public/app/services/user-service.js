angular.module('podcatcher')
    .service('User', function($filter, $http, localStorageService) {

        var self = this;

        this.username;
        this.authenticated;
        this.email;
        this.subscriptions = [];
        this.episodes = [];   

        // this.subscriptions = JSON.parse(localStorageService.get('subscriptions')) || [];
        // this.episodes = JSON.parse(localStorageService.get('episodes')) || [];

        this.authenticate = function() {
            $http.get(Routing.generate('get_user'))
            .success(function(response) {
                self.username = response.username;
                self.email = response.email;
                self.subscriptions = response.subscriptions;
                self.authenticated = true;

                $http.get(Routing.generate('get_listened')).success(function(episodes) {
                    self.episodes = episodes;
                });

            })
            .error(function(response) {
                self.authenticated = false;
                console.log('NOT AUTHENTICATED');
            });
        };

        this.persist = function() {
            localStorageService.add('username', this.username);
            localStorageService.add('email', this.email);
        };

        this.listen = function(episode) {
            console.log('listen');
            if(this.episodes.indexOf(episode.id) === -1) {
                this.episodes.push(episode.id);
                $http.post(Routing.generate('post_listen', { id: episode.id }));
            }
        };

        this.subscribe = function(podcast) {
            var found = $filter('getById')(this.subscriptions, podcast.id);
            if(found === false) {
                this.subscriptions.push(podcast);
                $http.post(Routing.generate('post_subscribe', { id: podcast.id }));
                // localStorageService.add('subscriptions', JSON.stringify(this.subscriptions));
            } else {
                this.subscriptions.splice(found, 1);
                $http.delete(Routing.generate('delete_unsubscribe', { id: podcast.id }));
                // localStorageService.add('subscriptions', JSON.stringify(this.subscriptions));
            }
        }; 

        this.isSubscribed = function(podcast) {
            return ($filter('getById')(this.subscriptions, podcast.id) !== false) ? true : false;
        };

        this.authenticate();

    });