/**
 * Our base of operations
 * 
 * @returns {PodCatcher}
 */
function PodCatcher() {
    this.AppKernel = new PodCatcher.prototype.AppKernel();
//    
//    Sammy(function() {
//        this.get('/', function() {
//            console.log('caught');
//        });
//    }).run();
}

/**
 * AppKernel. Starts all our little app thingies.
 * 
 * @param {type} configuration
 * @returns {undefined}
 */
PodCatcher.prototype.AppKernel = function() {
    //Bootstrap our application.
    this.PodcastFinder = new PodCatcher.PodcastFinder;
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
PodCatcher.PodcastFinder.prototype.categories = ko.observableArray();
PodCatcher.PodcastFinder.prototype.organizations = ko.observableArray();
PodCatcher.PodcastFinder.prototype.podcasts = ko.observableArray();

PodCatcher.PodcastFinder.prototype.pagination = {
    page: ko.observable(1),
    maxPageIndex: ko.observable(9),
    amount: ko.observable(8)
};


//Constructor
PodCatcher.PodcastFinder.prototype.__construct = function() {
    var self = this;
    this.refresh();
    $.get(Routing.generate('get_categories', { page: 1 }), function(categories) {
        self.categories($.map(categories, function(category) {
            return new self.entity.Category(category);
        }));
    });
    $.get(Routing.generate('get_organizations', { page: 1 }), function(organizations) {
        self.organizations($.map(organizations, function(organization) {
            return new self.entity.Organization(organization);
        }));
    });
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
PodCatcher.PodcastFinder.prototype.serialize = function() {
    var data = ko.toJS(this),
        organizations = "",
        categories = "";
    for(var i = 0; i < data["categories"].length; i++) {
        var category = data.categories[i];
        if(category.selected) {
            categories+="categories[]="+category.slug+"&";
        }
    }
    for(var i = 0; i < data["organizations"].length; i++) {
        var organization = data.organizations[i];
        if(organization.selected) {
            organizations+="organizations[]="+organization.slug+"&";
        }
    }
    return categories.substring(0,categories.length-1)+"&"+organizations.substring(0,organizations.length-1);
};

PodCatcher.PodcastFinder.prototype.refresh = function() {
    var self = this;
    $.getJSON(Routing.generate('get_podcasts', { page: self.pagination.page(), amount: self.pagination.amount() })+self.serialize(), function(response) {
        self.pagination.maxPageIndex(Math.ceil(response.total / self.pagination.amount()) - 1);
        delete response.total;
        self.podcasts($.map(response, function(podcast) {
            return new self.entity.Podcast(podcast);
        }));
    });
};

PodCatcher.PodcastFinder.prototype.toggleItem = function(item) {
    item.toggle();
    PodCatcher.PodcastFinder.prototype.refresh();
};

/**
 * Entities
 */
PodCatcher.PodcastFinder.prototype.entity = {};
//ListItem Abstract Class
PodCatcher.PodcastFinder.prototype.entity.ListItem = function(data) {
    this.name = data.name;
    this.slug = data.slug;
    this.selected = ko.observable(false);
};
PodCatcher.PodcastFinder.prototype.entity.ListItem.prototype.toggle = function() {
    this.selected(this.selected() ? false : true);
};
//Category
PodCatcher.PodcastFinder.prototype.entity.Category = function(data) {
    PodCatcher.PodcastFinder.prototype.entity.ListItem.call(this, data);
};
PodCatcher.PodcastFinder.prototype.entity.Category.prototype = Object.create(PodCatcher.PodcastFinder.prototype.entity.ListItem.prototype);
PodCatcher.PodcastFinder.prototype.entity.Category.prototype.link = function() {
    return Routing.generate('get_category', { slug: this.slug });
};
//Organization
PodCatcher.PodcastFinder.prototype.entity.Organization = function(data) {
    PodCatcher.PodcastFinder.prototype.entity.ListItem.call(this, data);
};
PodCatcher.PodcastFinder.prototype.entity.Organization.prototype = Object.create(PodCatcher.PodcastFinder.prototype.entity.ListItem.prototype);
PodCatcher.PodcastFinder.prototype.entity.Organization.prototype.link = function() {
    return Routing.generate('get_organization', { slug: this.slug });
};
//Podcast
PodCatcher.PodcastFinder.prototype.entity.Podcast = function(data) {
    this.name = data.name;
    this.slug = data.slug;
    this.image = data.image;
};
PodCatcher.PodcastFinder.prototype.entity.Podcast.prototype.link = function() {
    return Routing.generate('get_podcast', { slug: this.slug });
};


/**
 * Init this nonsense already
 */
$(document).ready(function(){
    ko.applyBindings(new PodCatcher());
});