var app     = app || {};
$           = jQuery;

let Library = Backbone.Collection.extend(
	{
		model : app.BookModel,
		initialize : function () {
			this.load();
		},
		load : function () {
			let library = this;
			$.ajax(
				{
					url: 'http://ionut.local/wp-json/bookAPI/v1/books/' + $( '#get_page_id' ).val(),
					type: 'get',
					data: {},
					success: function (response) {
						response.forEach(
							(jsonBook) => { let book  = JSON.parse( jsonBook );
                            let model = new app.BookModel(
									{
										title : book.title,
										author : book.author,
										genre : book.genre,
										summary : book.summary
									}
								);
							library.add( model );
							}
						);
					}
				}
			);
		}
	}
);
app.Library = new Library();
