/* js/BookModel.js */

import Backbone from "../../../../wp-includes/js/backbone";

(function ($) {

    /** Our code here **/

}(jQuery));

let book = Backbone.Model.extend({
    defaults : {
        'title' : 'Jungle book',
        'author' : 'J.K.Rowling',
        'genre' : 'Adventure',
    },
    url : ajaxurl + '?action=getBook',
});
