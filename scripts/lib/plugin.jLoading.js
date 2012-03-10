/*!
 * jQuery lightweight plugin boilerplate
 * Author: @ajpiano
 * Further changes: @addyosmani
 * Licensed under the MIT license
 */

;(function ($, undefined) {

    // Create the defaults, only once!
    var pluginName = 'jLoading',
        defaults = {
            progressid: "loadbar",
            layers: 10
        };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, this.options);
        
        this._defaults = defaults;
        this._name = pluginName;
        
        this.init();
        
    }

    Plugin.prototype.init = function () {
        // Place initialization logic here
        // You already have access to the DOM element and the options via the instance, 
        // e.g., this.element and this.options
        var self = this;
        this.buildbar();
        
        setTimeout(function(){
                if($('ul', $(self.element)).length > 0)
                    self.init();
            }, 5500);
    };
    
    
    Plugin.prototype.setOptions = function (options) {
        console.log('jLoading setOptions '+options);
    }
    
    /*Private*/
    Plugin.prototype.buildbar = function(){
        var jEl = $(this.element);
        $('ul', jEl).remove();
        var html = [];
        var liHtml = '<li><div id="%divid%" class="bar"></div></li>';
        html.push('<ul id="'+this.options['progressid']+'">');
        for(var i = 1; i < this.options['layers'] + 1; i++){
            html.push(liHtml.replace("%divid%", "layerFill"+i));
        }
        html.push('</ul>');
        jEl.html(html.join(""));
    }

    // A really lightweight plugin wrapper around the constructor, 
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return   this.each(function () {
                    if (!$.data(this, 'plugin_' + pluginName)) {
                        $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
                    }
                    else{
                        $.data(this, 'plugin_' + pluginName).setOptions(options);
                    }
                        
               
        });
    }

})(jQuery);