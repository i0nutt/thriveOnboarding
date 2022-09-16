$ = jQuery;
var app = app || {};
app.MyApp = Backbone.View.extend({
    el : $('#book-app'),

    initialize: function () {
        this.Library = app.Library;
        this.BookModel = app.BookModel;
        this.BookItem = app.BookItem;
        this.listenTo(this.Library,'add', (model) => {
            this.renderItem(model);
        });
    },

    events: {
        'submit' : 'onSubmit',
        'err'   : 'alertMe'
    },

    onSubmit: function (e) {
        e.preventDefault();
        let model = new this.BookModel({
            //id : this.Library.models.length,
            title : this.$('.title').val(),
            author : this.$('.author').val(),
            genre : this.$('.genre').val(),
            summary : this.$('.summary').val()
        });
        if (!model.isValid()) {
            this.err('Author format is probably wrong or there was a server problem');
            return;
        }
        model.save(
            null,
            {success : function () {
                this.Library.add(model);
            }},
            {error: function () {
            }}
        );

    },
    renderItem : function (model) {
        let item = new this.BookItem({model:model});
        this.$('#book table').append(item.render().el);
    },
    err : function (message) {
        window.alert(message);
    }
});