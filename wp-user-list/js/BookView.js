import Backbone from "../../../../wp-includes/js/backbone";

const BookModel = import('./BookModel');
console.log('her');
let myView = Backbone.View.extend({

    tagName: "div",

    className: "document-book",

    events: {
        "click .icon": "open",
        "click .button.edit": "openEditDialog",
        "click .button.delete": "destroy"
    },

    initialize: function () {
        //this.model = new BookModel();
        console.log(this.model);
        this.listenTo(this.model, "change", this.render);
    },
    render: function () {
        console.log(this.model);
        this.$el.html(this.template(this.model.attributes));
        return this;
    }
});
