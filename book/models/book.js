var app = app || {};

var global = 1;

app.BookModel = Backbone.Model.extend(
	{
		initialize : function () {
			this.set( 'id', global );
			global += 1;
		},
		url : function () {
			return 'http://ionut.local/wp-json/bookAPI/v1/book/' + this.get( "id" );
			return 'book/';
		},
		defaults: {
			post_id : jQuery( '#get_page_id' ).val(),
			title: '',
			author: '',
			genre: '',
			summary: '',
		},
		validate : function (attrs, options) {
			if (this.get( 'author' ).split( ' ' ).length < 2) {
				return "author must be a valid name";
			}
		}
	}
);
