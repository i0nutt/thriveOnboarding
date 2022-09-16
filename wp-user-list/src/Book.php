<?php

class Book
{
    // Names of Custom Post Type
    public string $postTypeNameSingle = 'Book';
    public string $postTypeNamePlural = 'Books';

    // Users ID's
    protected array $details = array();

    // Javascript
    public string $jsAdmin = '/js/BookModel.js';

    public function __construct()
    {
                $this->registerPostType(
                    $this->postTypeNameSingle,
                    $this->postTypeNamePlural
                );
    }
    public function getBook($post_id): string
    {
        $data = get_post_meta($post_id);
        return json_encode([
            'post_id' => $post_id,
            'data' =>  array(
                'title' => $data['title'][0],
                'author' => $data['author'][0],
                'genre' => $data['genre'][0])
        ]);
    }
    //from post
    public function savePost($post_id): void
    {
        if ($_POST['post_type'] !== strtolower($this->postTypeNameSingle)) {
            return;
        }
        if (!$this->canSaveData($post_id)) {
            return;
        }
        $data = [];
        $data['title'] = $_POST['title'];
        $data['author'] = $_POST['author'];
        $data['genre'] = $_POST['genre'];

        foreach ($data as $id => $field) {
            add_post_meta($post_id, $id, $field, true);
            // or
            update_post_meta($post_id, $id, $field);
        }
    }
    //from ajax Request
    public function saveBook(): void
    {
        $model = json_decode(file_get_contents("php://input"));
        $data = $model->data;
        $post_id =$model->post_id;
        if (!$this->canSaveData($model->post_id)) {
            return;
        }
        $ok = true;
        foreach ($data as $id => $field) {
            $ok &= add_post_meta($post_id, $id, $field, true);
            // or
            $ok &= update_post_meta($post_id, $id, $field);
        }
        if ($ok) {
            echo json_encode($model);
        } else {
            echo 0;
        }
        die();
    }
    private function canSaveData($post_id): bool
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }
        if ('page' == $_POST['post_type']) {
            if (! current_user_can('edit_page', $post_id)) {
                return false;
            }
        } else {
            if (! current_user_can('edit_post', $post_id)) {
                return false;
            }
        }
        return true;
    }
    private function registerPostType($single, $plural, $supports = null): void
    {
        $labels = array(
            'name' => _x($plural, 'post type general name'),
            'singular_name' => _x("$single", 'post type singular name'),
            'add_new' => _x("Add New $single", "$single"),
            'add_new_item' => __("Add New $single"),
            'edit_item' => __("Edit $single"),
            'new_item' => __("New $single"),
            'all_items' => __("All $plural"),
            'view_item' => __("View $single"),
            'search_items' => __("Search $plural"),
            'not_found' => __("No $plural found"),
            'not_found_in_trash' => __("No $single found in Trash"),
            'parent_item_colon' => '',
            'menu_name' => $plural
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => ( $supports ) ? $supports : array( 'title', 'author', 'custom-fields')
        );
        register_post_type($single, $args);
    }
}
