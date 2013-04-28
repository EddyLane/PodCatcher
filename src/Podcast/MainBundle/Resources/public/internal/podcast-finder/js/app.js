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
            self.active().getPodcastAction(this.params.slug);
        });
    }).run();
}


PodCatcher.PodcastController = function() {
    var self = this;
    this.template = ko.observable('view-podcast');
    this.podcast = ko.observable();
    this.getPodcastAction = function(slug) {
        self.template('view-podcast');
        $.get(Routing.generate('get_podcast', { _format: "json", slug: slug }), function(response) {
            self.podcast(new PodCatcher.entity.Podcast(response));
        });
    };
};


/**
 * The Podcast Finder TM module
 * @param {type} configuration
 * @returns {undefined}
 */
PodCatcher.PodcastFinder = function() {
    this.template = ko.observable('view-podcast-finder');
    this.__construct();
};

PodCatcher.PodcastFinder.prototype.page = ko.observable(1);
PodCatcher.PodcastFinder.prototype.maxPageIndex = ko.observable();
PodCatcher.PodcastFinder.prototype.amount = ko.observable(8);
    
PodCatcher.PodcastFinder.prototype.page.subscribe(function() {
    PodCatcher.PodcastFinder.prototype.refresh();
});
PodCatcher.PodcastFinder.prototype.amount.subscribe(function() {
    PodCatcher.PodcastFinder.prototype.refresh();
});
PodCatcher.PodcastFinder.prototype.getLinkForPage = function(page) {
    return Routing.generate('get_podcasts', { _format: "json", page: page, amount: this.amount(), categories: this.getSelectedCategorySlugs(), organizations: this.getSelectedOrganizationSlugs() });
};
PodCatcher.PodcastFinder.prototype.clearOrganizations = function() {
    ko.utils.arrayForEach(this.organizations(), function(organization) {
        organization.selected(false);
    });
    this.refresh();
};
PodCatcher.PodcastFinder.prototype.clearCategories = function() {
    ko.utils.arrayForEach(this.categories(), function(category) {
        category.selected(false);
    });
    this.refresh();
};

//Set our observable arrays up
PodCatcher.PodcastFinder.prototype.categories = ko.observableArray();
PodCatcher.PodcastFinder.prototype.organizations = ko.observableArray();
PodCatcher.PodcastFinder.prototype.podcasts = ko.observableArray();
PodCatcher.PodcastFinder.prototype.podcast = ko.observable();
PodCatcher.PodcastFinder.prototype.getSelectedCategories = function() {
    var selected = ko.utils.arrayFilter(this.categories(), function(category) {
        return category.selected();
    });
    return selected;
};
PodCatcher.PodcastFinder.prototype.getSelectedCategorySlugs = function() {
    var slugs = [];
    $.each(this.getSelectedCategories(), function(key, category){
        slugs.push(category.slug);
    });
    return slugs;
};
PodCatcher.PodcastFinder.prototype.getSelectedOrganizations = function() {
    return ko.utils.arrayFilter(this.organizations(), function(item) {
        return item.selected();
    });
};
PodCatcher.PodcastFinder.prototype.getSelectedOrganizationSlugs = function() {
    var slugs = [];
    $.each(this.getSelectedOrganizations(), function(key, organization){
        slugs.push(organization.slug);
    });
    return slugs;
};

//Constructor
PodCatcher.PodcastFinder.prototype.__construct = function() {
    var self = this;
    this.refresh();
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
};

PodCatcher.PodcastFinder.prototype.refresh = function() {
    var self = this;
    $.get(this.getLinkForPage(this.page()), function(response) {
        var maxPage = Math.floor(response.total / self.amount()) + 1;
        if(self.page() > maxPage) {
            self.page(maxPage);
        }
        self.maxPageIndex(maxPage);
        delete response.total;
        self.podcasts($.map(response, function(podcast) {
            return new PodCatcher.entity.Podcast(podcast);
        }));
    });
};
PodCatcher.PodcastFinder.prototype.goToPage = function(page) {
    this.page(page);
    this.refresh();
};
PodCatcher.PodcastFinder.prototype.toggleItem = function(item) {
    item.toggle();
    PodCatcher.PodcastFinder.prototype.refresh();
};

/**
 * Entities
 */
PodCatcher.entity = {};
//ListItem Abstract Class
PodCatcher.entity.ListItem = function(data) {
    return {
        name: data.name,
        slug: data.slug,
        selected: ko.observable(data.selected || false)
    };
};
PodCatcher.entity.ListItem.prototype.toggle = function() {
    this.selected(this.selected() ? false : true);
};

//Podcast
PodCatcher.entity.Podcast = function(data) {
    return {
        name: data.name,
        slug: data.slug,
        image: data.image,
        organizations: data.organizations,
        categories: data.categories
    };
};

/**
 * Init this nonsense already
 */
$(document).ready(function(){
    ko.applyBindings(new PodCatcher());
});