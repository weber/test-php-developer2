/**
 * Created by webs on 01.03.16.
 */
'use strict';

var ModelShorted = Backbone.Model.extend({
    url: '/api/shorted',
    read: function(data, model){
        Backbone.sync('read', this, {
            data:data,
            success: function(data) {
                model.set(data);
            }
            ,error: function() {
                console.log('Error!');
            }
        });
    }
})
var model_shorted = new ModelShorted();

var ShortedView = Backbone.View.extend({
    el: '.js-form_shortener',
    events: {
        'click .js-short': 'shortLink',
        'change .js-url_original': 'validate',
        'keyup .js-url_original': 'validate',
    },
    initialize: function () {
        this.model_shorted = model_shorted;
        this.model_shorted.on('change', this.render, this);
    },
    validate: function (e) {
        var url = $(e.currentTarget).val();
        var button = $( ".js-short" );
        var pattern = /^(http|https):\/\/([0-9a-zA-Z\-\_\.]){2,}/i;
        if (pattern.test(url)) {
            button.prop( "disabled", false );
        } else {
            button.prop( "disabled", true );
        }
    },
    shortLink: function (e) {
        var el_button = $(e.currentTarget);
        var el_input = $('.js-url_original');
        var data = {
            'link_original': el_input.val()
        };

        this.model_shorted.read(data, this.model_shorted);
    },
    render: function () {
        var el_container = $('.on-url_shortened');
        var short_link = this.model_shorted.toJSON().result;
        var tag_link = _.template('<a target="_blank" href="<%- url %>"><%- url %></a>');

        el_container.empty();
        el_container.html(tag_link({url : short_link}));
    }
});
var view_shorted = new ShortedView();

Backbone.emulateHTTP = true
Backbone.emulateJSON = true