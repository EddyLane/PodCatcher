/**
 * Our base of operations
 * 
 * @returns {PodCatcher}
 */
function PodCatcher() {
    
    var self = this;
    
    this.AppKernel = {
        podcastFinder: new PodCatcher.PodcastFinder(),
        podcastController: PodCatcher.PodcastController
    };
    
    this.active = ko.observable();
    
    Sammy(function() {
        
        this.get('/', function(context) {
            self.active(self.AppKernel.podcastFinder);
        });
        
        this.get(Routing.generate('get_podcast')+'/:slug', function(context) {
            self.active(self.AppKernel.podcastController);
            self.active().getPodcastAction(this.params.slug);
        });
        
    }).run();
}


PodCatcher.PodcastController = {
    
    template: ko.observable('view-podcast'),
    podcast: ko.observable(),
    
    getPodcastAction: function(slug) {
        var self = this;
        self.template('view-podcast');
        $.get(Routing.generate('get_podcast', { _format: "json", slug: slug }), function(response) {
            self.podcast(new PodCatcher.entity.Podcast(response));
        });
    }
};

/**
 * The Podcast Finder TM module
 * @param {type} configuration
 * @returns {undefined}
 */
PodCatcher.PodcastFinder = function() {
    this.__construct();
};
//Set our observable arrays up
PodCatcher.PodcastFinder.prototype = {
    
    sorts: [
        { name: 'Last Updated', sort: 'updated', direction: 'desc' },
        { name: 'A-Z', sort: 'name', direction: 'asc' }
    ],
    
    template: ko.observable('view-podcast-finder'),
    categories: ko.observableArray(),
    organizations: ko.observableArray(),
    podcasts: ko.observableArray(),
    page: ko.observable(1),
    maxPageIndex: ko.observable(),
    amount: ko.observable(8),
    sort: ko.observable(),
    
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
        
        this.sort(this.sorts[0]);
        
        this.page.subscribe(function() {
            self.refresh();
        });
        this.amount.subscribe(function() {
            self.refresh();
        });
        this.sort.subscribe(function() {
            self.refresh();
        });
        
        this.refresh();
    },
    
    getLinkForPage: function(page) {
        var sort = this.sort() || this.sorts[1];
        return Routing.generate('get_podcasts', { 
            _format: "json", 
            page: page, 
            amount: this.amount(), 
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
        var selected = ko.utils.arrayFilter(list, function(item) {
            return item.selected();
        });
        return selected;
    },
            
    getSelectedSlugs: function(list, slugs) {
        var slugs = [];
        ko.utils.arrayForEach(this.getSelected(list), function(item) {
            slugs.push(item.slug);
        });
        return slugs;
    },
            
    refresh: function() {

        var self = this,
            maxPage;
        
        $.get(this.getLinkForPage(this.page()), { organizations: this.getSelectedSlugs(this.organizations()), categories: this.getSelectedSlugs(this.categories()) },function(response) {
            maxPage = Math.floor(response.total / self.amount()) + 1;
            if (self.page() > maxPage) {
                self.page(maxPage);
            }
            self.maxPageIndex(maxPage);
            delete response.total;
            self.podcasts($.map(response, function(podcast) {
                return new PodCatcher.entity.ImageListItem(podcast[0]);
            }));
        });
    },
    
    goToPage: function(page) {
        this.page(page);
        this.refresh();
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
        
        this.pagination = {
            page: ko.observable(1),
            maxPageIndex: ko.observable(),
            amount: ko.observable(10)
        };
        
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

PodCatcher.entity.Podcast.prototype = {
    
    __construct: function() {
        var self = this;
        $.get(Routing.generate('get_podcast_episodes', { slug: this.slug, _format: 'json' }), function(response) {
            var maxPage = Math.floor(response.total / self.pagination.amount()) + 1;
            if(self.pagination.page() > maxPage) {
                self.page(maxPage);
            }
            self.pagination.maxPageIndex(maxPage);
            delete response.total;
            self.episodes($.map(response, function(episode) {
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