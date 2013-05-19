/**
 * Our base of operations
 * 
 * @returns {PodCatcher}
 */
function PodCatcher() {

    var self = this;

    this.AppKernel = {
        podcastFinder: new PodCatcher.PodcastFinder(),
        podcastController: new PodCatcher.PodcastController(),
        user: new PodCatcher.User({ username: $('#username'), password: $('#password'), csrf_token: $('#csrf_token')})
    };

    this.Player = PodCatcher.Player;
    this.active = ko.observable();

    Sammy(function() {

        this.get('/', function(context) {
            self.active(self.AppKernel.podcastFinder);
        });

        this.get(Routing.generate('get_podcast') + '/:slug', function(context) {
            self.active(self.AppKernel.podcastController);
            self.active().getPodcastAction(this.params);
        });

        this.get(Routing.generate('get_podcast') + '/:slug/:episodes', function(context) {
            self.active(self.AppKernel.podcastController);
            if (!self.active().podcast()) {
                self.active().getPodcastAction(this.params);
            } else {
                self.active().podcast().pagination.page(this.params.page);
            }
        });

    }).run();
}
soundManager.setup({
    url: '/bundles/podcastmain/soundmanager/swf',
    onready: function() {
        var episode = PodCatcher.Player.episode();
        if (episode) {
            var sound = soundManager.createSound({
                id: episode.id,
                url: episode.link,
                autoLoad: true,
            });

            var progress = PodCatcher.Player.progress();
           
            var loadAmount = (progress) ? progress.substring(0, progress.length - 1) : 0;
            loaded = false;

            soundManager.play(episode.id, {
                whileloading: function() {
                    console.log(!loaded && ((this.bytesLoaded / this.bytesTotal) * 100) >= loadAmount);
                    PodCatcher.Player.bytesLoaded(this.bytesLoaded);
//                    if (!loaded && ((this.bytesLoaded / this.bytesTotal) * 100) >= loadAmount) {
//                        sound.play({position: PodCatcher.Player.position()});
//                        loaded = true;
//                    }
                }

            });
        }
    }

});
soundManager.defaultOptions.whileplaying = function() {
    PodCatcher.Player.position(this.position);
    PodCatcher.Player.progress(((this.position / this.duration) * 100) + '%');
}

soundManager.defaultOptions.onload = function() {
    PodCatcher.Player.loading(false);
}

soundManager.defaultOptions.whileloading = function() {
    PodCatcher.Player.loading(true);
    PodCatcher.Player.bytesLoaded(this.bytesLoaded);
    PodCatcher.Player.buffer(((this.bytesLoaded / this.bytesTotal) * 100) + '%');
}


PodCatcher.User = function(configuration) {
    var self = this;
    this.username = ko.observable();
    this.error = ko.observable();
    this.login = function() {
        $.ajax({
            type: 'POST',
            url: Routing.generate('fos_user_security_check'),
            dataType: 'json',
            data: {
                _username: configuration.username.val(),
                _password: configuration.password.val(),
                _csrf_token: configuration.csrf_token.val()
            },
            success: function(response) {
                self.username(response.message);
            },
            error: function(xhr) {
                self.error($.parseJSON(xhr.responseText).message);
            }
        });

    };
    this.getListenedTo = function() {
        
    }
}

PodCatcher.Player = {

    playing: ko.observable(false),
    loading: ko.observable(false),
    buffer: ko.observable(0, {persist: 'playerBuffer'}),
    progress: ko.observable(0, {persist: 'playerProgress'}),
    position: ko.observable(0, {persist: 'playerPosition'}),
    episode: ko.observable(false, {persist: 'playerEpisode'}),
    bytesLoaded: ko.observable(0, {persist: 'playerLoaded'}),
    history: ko.observableArray([], {persist: 'playerHistory'}),
    stop: function() {
        soundManager.stopAll();
        
        this.buffer(0);
        this.progress(0);
        this.position(0);
        PodCatcher.Player.playing(false);
        PodCatcher.Player.loading(false);
        return PodCatcher.Player.episode();
    },
    play: function(episode) {

        PodCatcher.Player.playing(true);

        var isPlaying = PodCatcher.Player.stop();

        if (soundManager.getSoundById(episode.id)) {
            return soundManager.play(episode.id, {
                position: PodCatcher.Player.position()
            });
        }

        if(PodCatcher.Player.history.indexOf(episode.id) === -1) {
            PodCatcher.Player.history.push(episode.id);
            
            $.ajax({
                url: Routing.generate('listen_podcast_episode', { _format: 'json', slug: 'test', id: episode.id }),
                type: 'patch'
            });
            
        }

        PodCatcher.Player.episode(episode);

        var sound = soundManager.createSound({
            id: episode.id,
            url: episode.link,
            autoLoad: true,
            autoPlay: true,
        });

        return sound;
    }
}


PodCatcher.PodcastController = function() {
    this.template = ko.observable('view-podcast');
    this.podcast = ko.observable();
}

PodCatcher.PodcastController.prototype = {
    getPodcastAction: function(parameters) {
        var self = this;
        self.template('view-podcast');
        $.get(Routing.generate('get_podcast', {_format: "json", slug: parameters.slug}), function(response) {
            self.podcast(new PodCatcher.entity.Podcast($.extend(response, parameters)));
        });
    }

}

/**
 * The Podcast Finder TM module
 * @param {type} configuration
 * @returns {undefined}
 */
PodCatcher.PodcastFinder = function() {
    this.template = ko.observable('view-podcast-finder');
    this.podcasts = ko.observableArray();
    this.pagination = new PodCatcher.PodcastFinderPaginator(this.refresh, {_format: 'json', sort: this.sorts[0], amount: 16 }, this.podcasts);
};
//Set our observable arrays up
PodCatcher.PodcastFinder.prototype = {
    sorts: [
        {name: 'Last Updated', sort: 'updated', direction: 'desc'},
        {name: 'A-Z', sort: 'name', direction: 'asc'}
    ],
    getLinkForPage: function(page) {
        var sort = this.pagination.sort() || this.sorts[1];
        return Routing.generate('get_podcasts', {
            _format: "json",
            page: page,
            amount: this.pagination.amount(),
            sort: sort.sort,
            direction: sort.direction
        });
    },
    refresh: function(parameters) {

        var self = this,
                maxPage;

        $.get(Routing.generate('get_podcasts', {_format: 'json'}), {organizations: parameters.organizations, categories: parameters.categories, page: parameters.page, amount: parameters.amount, sort: parameters.sort.sort, direction: parameters.sort.direction}, function(response) {
            maxPage = Math.floor(response.metadata.total / self.amount()) + 1;
            if (self.page() > maxPage) {
                self.page(maxPage);
            }
            self.maxPageIndex(maxPage);
            self.results($.map(response.entities, function(podcast) {
                return new PodCatcher.entity.ImageListItem(podcast[0]);
            }));
        });
    },
    goToPage: function(page) {
        this.pagination.page(page);
    }

};

/**
 * Entities
 */
PodCatcher.entity = {
    ListItem: function(data) {
        this.name = data.name;
        this.slug = data.slug;
        this.selected = ko.observable(false);
    },
    ImageListItem: function(data) {
        PodCatcher.entity.ListItem.call(this, data);
        this.image = data.image;
    },
    Podcast: function(data) {
        this.name = data.name;
        this.slug = data.slug;
        this.categories = data.categories;
        this.organizations = data.organizations;
        this.image = data.image;
        this.episodes = ko.observableArray();
        this.pagination = new PodCatcher.Paginator(this.getEpisodesAction, {_format: 'json', slug: this.slug, page: data.page}, this.episodes);
    },
    Episode: function(data, cb) {

        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
        this.length = data.length;
        this.pub_date = data.pub_date;
        this.link = data.link;
        this.cb = cb;
        this.isPlaying = ko.computed(this.isPlaying, this);
        this.isNew = ko.computed(this.isNew, this);
    }

}

PodCatcher.entity.Episode.prototype = {
    play: function() {
        this.cb(this);
    },
    isPlaying: function() {
        var playing = PodCatcher.Player.episode();
        if (!playing) {
            return false;
        }
        return this.id === playing.id;
    },
    isNew: function() {
        return (PodCatcher.Player.history.indexOf(this.id) === -1) ? true : false;
    }
}

PodCatcher.BasePaginator = function(cb, parameters, results) {

    var self = this;

    this.refresh = cb;
    this.parameters = parameters;
    this.results = results;

    this.page = ko.observable(parameters.page || 1);

    this.maxPageIndex = ko.observable(parameters.max || 1);

    this.displayFirstPage = ko.computed(function() {
        var page = self.page(),
                maxPage = self.maxPageIndex(),
                newPage;

        newPage = (page > 3) ? page - 2 : 1;

        if (newPage < maxPage && newPage > (maxPage - 4)) {
            console.log('here');
            newPage -= (newPage - (maxPage - 4));
        }

        return newPage;
    });
    this.displayLastPage = ko.computed(function() {

        var page = self.page(),
                maxPage = self.maxPageIndex(),
                newPage;

        if (page < (maxPage - 3)) {
            newPage = page + 2;
        }
        else {
            newPage = maxPage;
        }


        if (newPage < 5 && maxPage > 4 && newPage < maxPage) {
            newPage += (5 - newPage);
        }

        return newPage;
    });

    this.amount = ko.observable(parameters.amount || 32);
    this.sort = ko.observable(parameters.sort || "");

    this.refresh(this.getRefreshParameters());
};

PodCatcher.Paginator = function(cb, parameters, results) {
    var self = this;

    PodCatcher.BasePaginator.call(this, cb, parameters, results);

    this.page.subscribe(function() {
        self.refresh(self.getRefreshParameters());
    });
    this.amount.subscribe(function() {
        self.refresh(self.getRefreshParameters());
    });
    this.sort.subscribe(function() {
        self.refresh(self.getRefreshParameters());
    });
}

PodCatcher.PodcastFinderPaginator = function(cb, parameters, results) {

    PodCatcher.BasePaginator.call(this, cb, parameters, results);

    var self = this,
        refresh = function() {
        self.refresh(self.getRefreshParameters(self.categories(), self.organizations()));
    };
    
    
    this.page.subscribe(refresh);
    this.amount.subscribe(refresh);
    this.sort.subscribe(refresh);    

    this.categories = ko.observableArray();
    this.organizations = ko.observableArray();

    this.loadListItems('get_categories', this.categories);
    this.loadListItems('get_organizations', this.organizations);

    this.toggleItem = function(item) {
        item.toggle();
        refresh();
    }
}

PodCatcher.PodcastFinderPaginator.prototype = {
    clearSelected: function(list) {
        ko.utils.arrayForEach(list, function(organization) {
            organization.selected(false);
        });
        PodCatcher.PodcastFinder.prototype.refresh();
    },
    getSelected: function(list) {
        return ko.utils.arrayFilter(list, function(item) {
            return item.selected();
        });
    },
    getSelectedSlugs: function(list) {
        var slugs = [];
        ko.utils.arrayForEach(this.getSelected(list), function(item) {
            slugs.push(item.slug);
        });
        return slugs;
    },
    getRefreshParameters: function(categories, organizations) {
        return $.extend(
                this.parameters,
                {page: this.page(), sort: this.sort(), amount: this.amount(), categories: this.getSelectedSlugs(categories), organizations: this.getSelectedSlugs(organizations)}
        );
    },
    loadListItems: function(url, result) {
        $.get(Routing.generate(url, {_format: "json"}), function(organizations) {
            result($.map(organizations, function(organization) {
                return new PodCatcher.entity.ListItem(organization);
            }));
        });
    }

};

PodCatcher.Paginator.prototype = {
    getRefreshParameters: function() {
        return $.extend(
                this.parameters,
                {page: this.page(), sort: this.sort(), amount: this.amount()}
        );
    }

}


PodCatcher.entity.Podcast.prototype = {
    getEpisodesAction: function(parameters) {
        var self = this;
        $.get(Routing.generate('get_podcast_episodes', {_format: 'json', slug: parameters.slug, page: parameters.page}), function(response) {

            var maxPage = Math.floor(response.metadata.total / parameters.amount) + 1;

            if (parameters.page > maxPage) {
                self.page(maxPage);
            }

            self.maxPageIndex(maxPage);

            delete response.metadata;
            self.results($.map(response, function(episode) {
                return new PodCatcher.entity.Episode(episode, PodCatcher.Player.play);
            }));
        });
    }

};

PodCatcher.entity.ListItem.prototype = {
    toggle: function() {
        this.selected(this.selected() ? false : true);
    }

};

/**
 * Init this nonsense already
 */
$(document).ready(function() {
    ko.applyBindings(new PodCatcher());
});