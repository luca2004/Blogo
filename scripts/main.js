myAppController = Backbone.Router.extend({
    routes: {
        // Define some URL routes
        'blog-:name': 'showBlogGallery',
        'thumbs-:id': 'showGallery',
        
        // Default
        '*actions': 'defaultAction'
    },
    models : {
            'home': null
        },
    views : {
            'home': null,
            'blogs': [],
            gallery: []
        },
    initialize: function(options){
    },
    initPageNavigate: function(){
        var self = this;
    
         // Bisogna intercettare la navigazione di jquery mobile e creare la page on-the-fly
         $(document).bind( "pagebeforechange", function( e, data ) { 
                var toPage = data.toPage;
                if($.type(toPage) === "string"){
                    var obj = $.mobile.path.parseUrl(toPage);
                    if(obj.hash != ''){    
                        var url = obj.hash.substr(1);
                        to = $( "[id='" + url + "']" );
                        if(to.length == 0){
                            to = $("<div data-role='page' id='" + url + "' data-add-back-btn='true'><div data-role='header'><h1>Blogo</h1></div><div data-role='content'><div id='loader'></div></div></div>").appendTo('body')
                            
                        }
                        $.mobile.changePage( to, data.options );
                    }
                }
            });
    },
    
    showBlogGallery: function(name){
        if(this.views.blogs[name] == null){
            this.views.blogs[name] = new myAppViews.BlogGalleryPage({   model: new myAppModels.blogGalleryCollection(), 
                                                                    name: name });
        }
    },
    showGallery: function(id){
        var link = $("li a#"+id).attr('data-link');
        console.log(link);
        if(this.views.gallery[id] == null){
            this.views.gallery[id] = new myAppViews.SlideShowPage({   model: new myAppModels.thumbGalleryCollection(), 
                                                                    link: link });
        }
    },
    defaultAction: function(actions){
        if(this.views.home == null){
            var self = this;
            this.views['home'] = new myAppViews.HomePage({  model: new myAppModels.homepageCollection() });
        }
    }
});

//--------------------------------------------------------------------------------------------------------------------------------//
var myApplication = {
        
    controller: new myAppController(),
        
    init : function(){
        var self = this;
        this.controller.initPageNavigate();
        Backbone.history.start();
    },
    
    
    jQMApplyStyles: function(el){
        el.find('ul[data-role]').listview();
        el.find('div[data-role="fieldcontain"]').fieldcontain();
        el.find('button[data-role="button"]').button();
        el.find('input,textarea').textinput();
        return el.page();
    }
}

//Start-up Function
$(document).ready(function(){
    myApplication.init();
});
$(document).bind("mobileinit", function(){
    myApplication.init();
});