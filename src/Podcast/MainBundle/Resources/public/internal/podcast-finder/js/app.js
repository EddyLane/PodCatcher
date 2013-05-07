/**
 * Our base of operations
 * 
 * @returns {PodCatcher}
 */
function PodCatcher() {
    
    var self = this;
    
    this.AppKernel = {
        podcastFinder: new PodCatcher.PodcastFinder(),
        podcastController: new PodCatcher.PodcastController()
    };
    
    this.active = ko.observable();
    
    Sammy(function() {
        
        this.get('/', function(context) {
            self.active(self.AppKernel.podcastFinder);
        });
        
        this.get(Routing.generate('get_podcast')+'/:slug', function(context) {
            self.active(self.AppKernel.podcastController);
            self.active().getPodcastAction(this.params);
        });
                
        this.get(Routing.generate('get_podcast')+'/:slug/:episodes', function(context) {
            self.active(self.AppKernel.podcastController);
            if(!self.active().podcast()) {
                self.active().getPodcastAction(this.params);
            } else {
                self.active().podcast().pagination.page(this.params.page);
            }
        });
        
    }).run();
}

PodCatcher.PodcastController = function() {
    this.template = ko.observable('view-podcast');
    this.podcast = ko.observable();
}

PodCatcher.PodcastController.prototype = {
    
    getPodcastAction: function(parameters) {
        var self = this;
        self.template('view-podcast');
        $.get(Routing.generate('get_podcast', { _format: "json", slug: parameters.slug }), function(response) {
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
    this.categories = ko.observableArray();
    this.organizations = ko.observableArray();
    this.podcasts = ko.observableArray();
    this.pagination = new PodCatcher.Paginator(this.refresh, { _format: 'json', sort: this.sorts[0] }, this.podcasts);
    this.__construct();
};
//Set our observable arrays up
PodCatcher.PodcastFinder.prototype = {
    
    sorts: [
        { name: 'Last Updated', sort: 'updated', direction: 'desc' },
        { name: 'A-Z', sort: 'name', direction: 'asc' }
    ],

    __construct: function() {
        var self = this;
        
        $.get(Routing.generate('get_categories', { _format: "json" }), function(categories) {
            self.categories($.map(categories, function(category) {
                return new PodCatcher.entity.ListItem(category);
            }));
        });
        $.get(Routing.generate('get_organizations', { _format: "json" }), function(organizations) {
            self.organizations($.map(organizations, function(organization) {
                return new PodCatcher.entity.ListItem(organization);
            }));
        });
        
    },
    
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
            
    getSelectedSlugs: function(list, slugs) {
        var slugs = [];
        ko.utils.arrayForEach(this.getSelected(list), function(item) {
            slugs.push(item.slug);
        });
        return slugs;
    },
            
    refresh: function(parameters) {

        var self = this,
            maxPage;
        
        $.get(Routing.generate('get_podcasts', { _format: 'json' }),{ organizations: parameters.organizations, categories: parameters.categories, page: parameters.page, amount: parameters.amount, sort: parameters.sort.sort, direction: parameters.sort.direction }, function(response) {
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
    },
            
    toggleItem: function(item) {
        item.toggle();
        PodCatcher.PodcastFinder.prototype.refresh();
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
        this.pagination = new PodCatcher.Paginator(this.getEpisodesAction, { _format: 'json', slug: this.slug, page: data.page }, this.episodes);
        
        this.__construct();
    },
            
    Episode: function(data) {
        this.name = data.name;
        this.description = data.description;
        this.length = data.length;
        this.pub_date = data.pub_date;
        this.link = data.link;
    }
    
}


PodCatcher.Paginator = function(cb, parameters, results) {
    
    var self = this;
    
    this.refresh = cb;
    this.parameters = parameters;
    this.results = results;
    
    this.page = ko.observable(parameters.page || 1);
    this.maxPageIndex = ko.observable(parameters.max || 1);
    this.amount = ko.observable(parameters.amount || 32);
    this.sort = ko.observable(parameters.sort || "");
    
    this.page.subscribe(function() {
        self.refresh(self.getRefreshParameters());
    });
    this.amount.subscribe(function() {
        self.refresh(self.getRefreshParameters());
    });
    this.sort.subscribe(function() {
        self.refresh(self.getRefreshParameters());
    });    
    
    this.refresh(this.getRefreshParameters());
};

PodCatcher.Paginator.prototype = {
    getRefreshParameters: function() {
        return $.extend(
                this.parameters,
                { page: this.page(), sort: this.sort(), amount: this.amount() }
        );
    }
}

PodCatcher.entity.Podcast.prototype = {
    
    __construct: function() {},
    
    getEpisodesAction: function(parameters) {
        var self = this;
        $.get(Routing.generate('get_podcast_episodes', { _format: 'json', slug: parameters.slug, page: parameters.page }), function(response) {
            
            var maxPage = Math.floor(response.metadata.total / parameters.amount) + 1;
            
            if(parameters.page > maxPage) {
                self.page(maxPage);
            }

            self.maxPageIndex(maxPage);

            delete response.metadata;            
            self.results($.map(response, function(episode) {
                return new PodCatcher.entity.Episode(episode);
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
$(document).ready(function(){
    ko.applyBindings(new PodCatcher());
});