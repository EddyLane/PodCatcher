/**
 * Our base of operations
 * 
 * @returns {PodCatcher}
 */
function PodCatcher() {
    this.AppKernel = new PodCatcher.prototype.AppKernel();
    
    Sammy(function() {
        this.get('/', function() {
            console.log('caught');
        });
    }).run();
}

/**
 * AppKernel. Starts all our little app thingies.
 * 
 * @param {type} configuration
 * @returns {undefined}
 */
PodCatcher.prototype.AppKernel = function() {
    //Bootstrap our application.
    this.PodcastFinder = new PodCatcher.PodcastFinder();
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
//Constructor
PodCatcher.PodcastFinder.prototype.__construct = function() {
    var self = this;
    $.get(Routing.generate('get_categories', { page: 1 }), function(categories) {
        self.categories($.map(categories, function(category) {
            return new PodCatcher.PodcastFinder.prototype.entity.Category(category);
        }));
    });
    $.get(Routing.generate('get_organizations', { page: 1 }), function(organizations) {
        self.organizations($.map(organizations, function(organization) {
            return new PodCatcher.PodcastFinder.prototype.entity.Organization(organization);
        }));
    });
};
PodCatcher.PodcastFinder.prototype.toggleItem = function(item) {
    
    item.toggle();
    
    var data = ko.toJS(PodCatcher.PodcastFinder.prototype),
        organizations = "",
        categories = "";

    for(var i = 0; i < data["categories"].length; i++) {
        if(data.categories[i].selected) {
            categories+="categories[]="+data.categories[i].slug+"&";
        }
    }
    for(var i = 0; i < data["organizations"].length; i++) {
        if(data.organizations[i].selected) {
            organizations+="organizations[]="+data.organizations[i].slug+"&";
        }
    }
    
    $.get(Routing.generate('get_podcasts')+"?"+categories.substring(0,categories.length-1)+"&"+organizations.substring(0,organizations.length-1), PodCatcher.PodcastFinder.prototype.podcasts);
}

/**
 * Entities
 */
PodCatcher.PodcastFinder.prototype.entity = {}
//ListItem Abstract Class
PodCatcher.PodcastFinder.prototype.entity.ListItem = function(data) {
    this.name = data.name;
    this.slug = data.slug;
    this.selected = ko.observable(false);
};
PodCatcher.PodcastFinder.prototype.entity.ListItem.prototype.toggle = function() {
    this.selected(this.selected() ? false : true);
}
//Category
PodCatcher.PodcastFinder.prototype.entity.Category = function(data) {
    PodCatcher.PodcastFinder.prototype.entity.ListItem.call(this, data);
};
PodCatcher.PodcastFinder.prototype.entity.Category.prototype = Object.create(PodCatcher.PodcastFinder.prototype.entity.ListItem.prototype);
PodCatcher.PodcastFinder.prototype.entity.Category.prototype.link = function() {
    return Routing.generate('get_category', { slug: this.slug });
}
//Organization
PodCatcher.PodcastFinder.prototype.entity.Organization = function(data) {
    PodCatcher.PodcastFinder.prototype.entity.ListItem.call(this, data);
};
PodCatcher.PodcastFinder.prototype.entity.Organization.prototype = Object.create(PodCatcher.PodcastFinder.prototype.entity.ListItem.prototype);
PodCatcher.PodcastFinder.prototype.entity.Organization.prototype.link = function() {
    return Routing.generate('get_organization', { slug: this.slug });
}




/**
 * Init this nonsense already
 */
$(document).ready(function(){
    ko.applyBindings(new PodCatcher());
});