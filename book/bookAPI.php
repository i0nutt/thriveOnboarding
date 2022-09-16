<?php

function registerRoutes(): void {
	register_rest_route(
		'bookAPI/v1',
		'/books/(?P<id>\d+)',
		array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => 'getBooks',
		)
	);
	register_rest_route(
		'bookAPI/v1',
		'book/(?P<id>\d+)',
		array(
			'methods'  => WP_REST_Server::EDITABLE,
			'callback' => 'createBook',
		)
	);
}

function getBooks( $request ) {
	if ( ! isset( $request['id'] ) ) {
		return json_encode( array( 'success' => false ) );
	}

	return get_post_meta( $request['id'], 'myBookApp', true );
}

function createBook( $request ) {
	if ( isset( $request['post_id'] ) ) {
		$postID = $request['post_id'];
	} else {
		return json_encode( array( 'success' => false ) );
	}
	if ( ! (
		isset( $request['title'] ) &&
		isset( $request['author'] ) &&
		isset( $request['genre'] ) &&
		isset( $request['summary'] )
	)
	) {
		return json_encode( array( 'success' => false ) );
	}
	$model = array(
		'title'   => $request['title'],
		'author'  => $request['author'],
		'genre'   => $request['genre'],
		'summary' => $request['summary'],
	);
	$data  = get_post_meta( $postID, 'myBookApp', true );
	if ( $data === false ) {
		return json_encode( array( 'success' => false ) );
	}
	if ( $data == '' ) {
		$data = array();
	}
	$data[] = json_encode( $model );
	if ( ! update_post_meta( $postID, 'myBookApp', $data ) ) {
		return json_encode( array( 'success' => false ) );
	}

	return json_encode( array( 'success' => true ) );
}

add_action( 'rest_api_init', 'registerRoutes' );

function addID(): void {
	$page_id = get_the_ID();
	echo '<input type="hidden" id="get_page_id" value="' . $page_id . '">';
}

add_action( 'template_redirect', 'addID' );
