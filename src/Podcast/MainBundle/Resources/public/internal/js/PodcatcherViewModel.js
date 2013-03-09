var podcast = {
    dom: {
        progress: $('#progress')
    }
}

soundManager.setup({
    url: '/Podcasts/web/bundles/podcasttool/swf'
});

soundManager.defaultOptions.whileplaying = function() {
    $('#progress').css('width', ((this.position / this.duration) * 100) + '%');
}

soundManager.defaultOptions.whileloading = function() {
    $('#buffer').css('width', ((this.bytesLoaded/this.bytesTotal) * 100) + '%');
}

var base_url = "/";
var mapping = {
    key: function(data) {
        return ko.utils.unwrapObservable(data.id);
    },
    create: function(options) {
        return new Podcast(options.data);
    },
    "children": {
        create: function(options) {
            return new Episode(options.data);
        },
        key: function(data) {
            return ko.utils.unwrapObservable(data.id);
        }
    }
}


var Episode = function(data)
{
    var self = this;
    this.id = data.id, this.name = data.name, this.hash = data.hash, this.link = data.link, this.description = data.description, this.isNew = ko.observable(data.listenedBy.length > 0 ? true : false);
    var date = new Date.parse(data.pub_date);
    this.pub_date = date.toString('dddd, MMMM d yyyy')  
    
    this.isNew.subscribe(function(value) {
        if(value) {
            $.ajax({
                type: "patch",
                url: base_url+"episodes/"+self.id+"/listen"
            });
        } else {
            $.ajax({
                type: "patch",
                url: base_url+"episodes/"+self.id+"/unlisten"
            });
        }
    });

}



var Podcast = function(data)
{
    
    var prev;
    
    var self = this;
    this.loading = ko.observable(false);
    ko.mapping.fromJS(data, {}, this);
    
    this.episodes = (!data.episodes) ? [] : $.map(data.episodes, function(episode) {
        return new Episode(episode);
    });
    
    this.selected = ko.observable();
    
    this.getImage = ko.computed(function() {
        return self.image ? self.image() : "http://placehold.it/350x350&text="+self.name();
    });
    
    this.subscribed = ko.computed(function(){
        return user.isSubscribed(ko.mapping.toJS(self)) ? true : false;
    });
    
    this.playEpisode = function(episode, event)
    {
        episode.isNew(true);
        var id = episode.id;
        self.selected(episode);
        soundManager.stopAll();
        
        if(soundManager.getSoundById(id)) {
            soundManager.play(id);
        } else {
            
            $.ajax({
                type: "patch",
                url: base_url+"episodes/"+id+"/listen"
            });
            
            soundManager.createSound({
                id: id,
                url: episode.link,
                autoLoad: true,
                autoPlay: true
            });
        }
        var target = $(event.target).parent();
        target.addClass('info').siblings().removeClass('info');
        return true;
    }
}

Podcast.prototype.nowPlaying = function(episode)
{
    if (user.episodeId() == episode.id) {
        return true;
    }
    return false;
}

Podcast.prototype.subscribe = function() 
{
    var self = this, url = base_url+"podcasts/"+self.slug();
    url += self.subscribed() ? "/unsubscribe" : "/subscribe"
    self.subscribed() ? user.removeSubscription(ko.mapping.toJS(self)) : user.addSubscription(ko.mapping.toJS(self));

    $.ajax({
        url: url, 
        type: "patch"
    });
}

function User()
{
    var self = this;
    this.authenticated = ko.observable(false);
    this.error = ko.observable();
    this.subscriptions = ko.observableArray();
    this.username = ko.observable();
    this.queue = ko.observableArray();
    this.episodeId = ko.observable(false);

    this.authenticate = function(response) {
        if(response) {
            self.subscriptions.removeAll();
            self.authenticated(true);
            self.username(response.username);
            self.subscriptions(response.subscriptions);
            return true;
        }
    }
    
    this.removeSubscription = function(podcast) {
        var match = ko.utils.arrayFirst(self.subscriptions(), function(item) {
            return podcast.id === item.id;
        });
        self.subscriptions.remove(match);
    }
    
    this.addSubscription = function(podcast) {
        self.subscriptions.push(podcast);
    }

    this.login = function() {
        $.post($('form#login').attr('action'), $('form#login').serialize(), self.authenticate);
    }


    this.isSubscribed = function(podcast) {
        var unmapped = ko.mapping.toJS(self.subscriptions());
        var match = ko.utils.arrayFirst(unmapped, function(item) {
            return podcast.id === item.id;
        });
        return match;
    }
    
    //$.get(base_url+"users/authenticate", function(response){
    //    self.authenticate(response);
    //});
}


function Directory(data) {
    
    var self = this;
    this.indexPodcasts = ko.observableArray();
    this.amount = 12;
    
    this.pagination = {
        page: ko.observable(1),
        next: function() {
            var nextPage = (this.page() + 1), length = self.indexPodcasts().length;
            this.page(nextPage);
        },
        previous: function() {
            var previousPage = this.page();
            this.page(previousPage - 1);
        }
    }


    this.thumbnails = ko.computed(function() {
        var podcasts = self.indexPodcasts, start = (self.pagination.page() - 1) * self.amount, end = start + self.amount;
        return podcasts.slice(start,end);
    });

    

    

}

function PodcatcherViewModel()
{
    var self = this;
    this.podcast = ko.observable(false);
    this.podcastId = ko.observable();
    this.directory = new Directory();
    user = new User();

    this.main = function() {
        self.podcast(false);
        self.podcastId(false);
        $.get(base_url+"podcasts", function(response) {
            self.directory.indexPodcasts(ko.mapping.fromJS(response,mapping));
        });
    }

    this.showPodcast = function(podcastSlug, episodeSlug) {
        $.get(base_url+"podcasts/"+podcastSlug, function(response) {
            var podcast = ko.mapping.fromJS(response,mapping);
            self.podcast(podcast);
            self.podcastId(response.id);
         
            if(user.authenticated()) {
                self.podcast().subscribed();
            }
        });
    }
    
    this.loadSubscription = function(podcast) {
        self.podcastId(podcast.id);
        location.hash = "podcasts/"+podcast.slug;
    }

    this.loadPodcast = function(podcast,e) {
        podcast.loading(true);        
        self.podcastId(podcast.id());
        location.hash = "podcasts/"+podcast.slug();
    }

    this.loadIndex = function() {
        location.hash = "";
    }

    Sammy(function() {

        this.get('#index', function() {
            self.main();
        });

        this.get('#podcasts/:podcastSlug', function() {
            self.showPodcast(this.params.podcastSlug);
        });

//        this.get('', function() {
//            this.app.runRoute('get', '#index')
//        });

    }).run();
}

(function(){
    
    var user, app = new PodcatcherViewModel();
    ko.applyBindings(app);
    
    $('#progress').click(function(e) {
         var posX = $(this).offset().left;
         console.log(soundManager.bytesLoaded / soundManager)
    });
    
    $('select').select2();

    
})();

ko.bindingHandlers.uniform = {
    init: function(element, valueAccessor, allBindingsAccessor, viewModel) {
                              
        // get the options that were passed in
        var options = allBindingsAccessor().jeditableOptions || { };

        // set the value on submit and pass the editable the options
        $(element).uniform(options);


    },

    //update the control when the view model changes
    update: function(element, valueAccessor, allBindingsAccessor, viewModel) {
                                    
        var value = ko.utils.unwrapObservable(valueAccessor());
        
        $.uniform.update(element);
     
    }
};