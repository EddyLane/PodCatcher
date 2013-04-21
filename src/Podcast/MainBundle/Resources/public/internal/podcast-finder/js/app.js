/**
 * Our base of operations
 * 
 * @returns {PodCatcher}
 */
function PodCatcher() {
    this.AppKernel = new PodCatcher.prototype.AppKernel();
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
//Entities
PodCatcher.PodcastFinder.prototype.entity = {}
PodCatcher.PodcastFinder.prototype.ListItem = function(data) {
    this.name = data.name;
    this.selected = ko.observable(false);
};
PodCatcher.PodcastFinder.prototype.entity.Category = function(data) {
    PodCatcher.PodcastFinder.prototype.ListItem.call(this, data);
};
PodCatcher.PodcastFinder.prototype.entity.Organization = function(data) {
    PodCatcher.PodcastFinder.prototype.ListItem.call(this, data);
};
//Function to filter/populate our podcasts observableArray
PodCatcher.PodcastFinder.prototype.filter = function() {
    
};

/**
 * Init this nonsense already
 */
$(document).ready(function(){
    ko.applyBindings(new PodCatcher());
});