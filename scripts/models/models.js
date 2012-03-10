//--------------------------------------------------------------------------------------------------------------------------------//
myAppModels.homepageModel = Backbone.Model.extend({
    url: '',
    defaults: {
        'id'    :   '',
        'href'  :   '',
        'name'  :   ''
    }
});
myAppModels.homepageCollection = Backbone.Collection.extend({
    url: 'php/server.php?action=getblogs',
    model: myAppModels.homepageModel
});

//--------------------------------------------------------------------------------------------------------------------------------//
myAppModels.blogGalleryModel = Backbone.Model.extend({
    url: '',
    defaults: {
        'id'    :   '',
        'thumb'  :   '',
        'link'  :   '',
        'label'  :   ''
    }
});
myAppModels.blogGalleryCollection = Backbone.Collection.extend({
    urlRoot : 'php/server.php?action=gallerylist',
    model: myAppModels.blogGalleryModel,
    setUrl: function(blog_name){
        this.url = this.urlRoot + '&name=' + blog_name;
    }
});

//--------------------------------------------------------------------------------------------------------------------------------//
myAppModels.thumbGalleryModel = Backbone.Model.extend({
    url: '',
    defaults: {
        'id'        :   '',
        'thumbimg'  :   '',
        'baseimg'   :   '',
        'bigimg'    :   '',
        'title'     :   ''
    }
});
myAppModels.thumbGalleryCollection = Backbone.Collection.extend({
    urlRoot : 'php/server.php?action=gallerythumbs',
    model: myAppModels.thumbGalleryModel,
    setUrl: function(dataLink){
        this.url = this.urlRoot + '&link=' + dataLink;
    }
});
