var app = app || {};

app.BookItem = Backbone.View.extend({
    tagName : 'tr',
    initialize : function () {

    },
    render : function () {
        let data = this.model.toJSON();
        let html = "<td>" + data.title + "</td>";
        html += "<td>" + data.author + "</td>";
        html += "<td>" + data.genre + "</td>";
        html += "<td>" + data.summary + "</td>";
        this.$el.append(html);
        return this;
    }
});