<?php
require_once __DIR__ . '/abstract-testcase.php';
require_once __DIR__ . '/../bookAPI.php';

class TestCreate extends LibraryUnitTest {
	public function testGetBooksWrongRequest() {
		$this->assertFalse( getBooks( array() ) );
	}

	public function test_get_values() {
		$post_id = 1;
		$request = array(
			'id'      => '',
			'post_id' => $post_id,
			'title'   => '',
			'author'  => '',
			'genre'   => '',
			'summary' => '',
		);

		createBook( $request );
		$arr = getBooks( array( 'id' => $post_id ) );

		$this->assertIsArray( $arr );
	}

	public function testCreateBook() {
		$post_id = 1;
		$request = array(
			'id'      => '',
			'post_id' => $post_id,
			'title'   => '',
			'author'  => '',
			'genre'   => '',
			'summary' => '',
		);

		createBook( $request );
		$arr = getBooks( array( 'id' => $post_id ) );
		$arr = json_decode( $arr[ count( $arr ) - 1 ], true );

		unset( $request['id'] );
		unset( $request['post_id'] );

		$this->assertEqualSetsWithIndex( $arr, $request );
	}
}
