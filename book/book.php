<?php
/**
 * Plugin Name: Book
 * Author: Ionut T.
 */

include( __DIR__ . '/bookAPI.php' );

add_action( 'wp_enqueue_scripts', 'registerScripts' );
add_action( 'wp_enqueue_scripts', 'enqueueMyScripts' );
add_action( 'init', 'rest_api_init' );
//$e = get_rest_url(0, '/book');

function registerScripts(): void {
	wp_register_script( 'backbone-localstorage', 'C:\wamp64\www\wp-local\wp-includes\js\backbone.js', array( 'backbone' ) );
	wp_register_script(
		'book-view-app',
		plugins_url( 'views/app.js', __FILE__ ),
		array( 'book-collection', 'book-view-view' ),
		0.1,
		true
	);
	wp_register_script( 'book-model', plugins_url( 'models/book.js', __FILE__ ), array(), 0.1, true );
	wp_register_script( 'book-collection', plugins_url( 'collections/list.js', __FILE__ ), array( 'book-model' ), 0.1, true );
	wp_register_script( 'book-view-view', plugins_url( 'views/book.js', __FILE__ ), array(), 0.1, true );
	wp_register_script( 'book', plugins_url( 'book.js', __FILE__ ), array(), 0.1, true );
}

function enqueueMyScripts():void {
	wp_enqueue_script( 'backbone-localstorage' );
	wp_enqueue_script( 'book-view-app' );
	wp_enqueue_script( 'book-model' );
	wp_enqueue_script( 'book-collection' );
	wp_enqueue_script( 'book-view-view' );
	wp_enqueue_script( 'book' );
}
add_shortcode( 'book', 'loadShortCode' );

function loadShortCode():void {
	?>
	<div id = 'book-app'>
		<div id = 'book'>
			<table>
				<tr>
					<th>Title</th>
					<th>Author</th>
					<th>Genre</th>
					<th>Summary</th>
				</tr>
			</table>
			<?php
			if ( is_user_logged_in() ) {
				$user  = wp_get_current_user();
				$roles = $user->roles;
				if ( ! ( in_array( 'administrator', $roles ) || in_array( 'editor', $roles ) || in_array( 'author', $roles ) ) ) {
					return;
				}
			}
			?>
			<form id = 'createBook' method = 'post' >
				<input type = 'text' name ='title' class = 'title' placeholder= 'Title' value = '' required>
				<input type = 'text' name ='author' class = 'author' placeholder= 'Author' value = '' required>
				<input type = 'text' name ='genre' class = 'genre' placeholder= 'Genre' value = '' required>
				<input type = 'text' name ='summary' class = 'summary' placeholder= 'Summary' value = '' required>
				<button type = 'submit'>Create</button>
			</form>
		</div>
	</div>
	<?php
}
