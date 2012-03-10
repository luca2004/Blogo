//--------------------------------------------------------------------------------------------------------------------------------//
myAppViews.HomePage = Backbone.View.extend({
    model: null,
    // Cache the template function for a single item.
    template: _.template($('#content-bloglist-template').html()),
    
    // Methods
    initialize : function(options){
        var self = this;
        
        this.model.fetch({ success: function(){ self.render() } });
    },
    render : function(){
        this.el = $('.ui-page-active');
        
        var html = [];
        var templateItem = _.template($('#blogitem-template').html())
        var templateItemDivider = _.template($('#blogitem-divider-template').html())
        this.model.each(function(item) {
                var jsonItem = item.toJSON();
                if(jsonItem.type == 'category')
                    html.push( templateItemDivider(jsonItem) );
                else
                    html.push( templateItem(jsonItem) );
            });
           
        this.el.find('div[data-role="content"]').html( this.template( { 'title': 'Le gallery dei blog di BLOGO.it', 
                                         'listItems' : html.join('') } ) );   

        // Workaround per fare applicare jquery styles                                    
        return myApplication.jQMApplyStyles(this.el);
    }
});

//--------------------------------------------------------------------------------------------------------------------------------//
myAppViews.BlogGalleryPage = Backbone.View.extend({
    model: null,
    nameBlog: '',
    // Cache the template function for a single item.
    template: _.template($('#content-bloglist-template').html()),
    
    // Methods
    initialize : function(options){
        var self = this;
        
        $('#loader').jLoading();
        this.nameBlog = options.name;
        this.model.setUrl(options.name);
        this.model.fetch({ success: function(){ self.render() } });
    },
    render : function(){
        this.el = $('.ui-page-active');
        this.el.find('div[data-role="header"] h1').html(this.nameBlog.toUpperCase());
        
        var html = [];
        var templateItem = _.template($('#bloggalleryitem-template').html())
        this.model.each(function(item) {
                html.push( templateItem(item.toJSON()) );
            });
        this.el.find('div[data-role="content"]').html( this.template( { 
                                         'title': 'Blogo', 
                                         'listItems' : html.join('') } ) );   

        // Workaround per fare applicare jquery styles                                    
        return myApplication.jQMApplyStyles(this.el);    
    }
});

//--------------------------------------------------------------------------------------------------------------------------------//
myAppViews.SlideShowPage = Backbone.View.extend({
    model: null,
    // Cache the template function for a single item.
    template: _.template($('#content-slideshow-template').html()),
    
    // Methods
    initialize : function(options){
        var self = this;
        
        $('#loader').jLoading();
        this.model.setUrl(options.link);
        this.model.fetch({ success: function(){ self.render() } });
    },
    render : function(){
        this.el = $('.ui-page-active');
        
        var html = [];
        var templateItem = _.template($('#slideshowitem-template').html())
        var title = '';
        this.model.each(function(item) {
                 var itemJson = item.toJSON()
                 title = itemJson.title;   
                 html.push( templateItem(itemJson) );
            });
        this.el.find('div[data-role="header"] h1').html(title);
        
        this.el.find('div[data-role="content"]').addClass('ui-content');    
        this.el.find('div[data-role="content"]').html( this.template( { 
                                         'title': 'Blogo', 
                                         'listItems' : html.join('') } ) ); 
                                         
		var PhotoSwipe = window.Code.PhotoSwipe;
        
        console.log('w ' + $(document).width());
        $('.gallery li img').css('height', $(document).width() / 3);
        
        var currentPage = this.el,
            options = {},
            photoSwipeInstance = this.el.find('ul.gallery a').photoSwipe(options, currentPage.attr('id'));
            
        // Workaround per fare applicare jquery styles                                    
        return myApplication.jQMApplyStyles(this.el);    
    }
});
