<?php
/**
 * Plugin Name: Library
 * Author: Ionut T.
 */

class ListUsersWidget extends WP_Widget
{
    public array $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    public function __construct()
    {
        parent::__construct(
            'userList',  // Base ID
            'User List'   // Name
        );
        add_action('widgets_init', function () {
            register_widget('ListUsersWidget');
        });
    }


    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        $this->listUsersHtml($instance);
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        // TODO outputs the options form in the admin
    }

    public function update($new_instance, $old_instance)
    {
        // TODO processes widget options to be saved
    }
    private function listUsersHtml($users)
    {
        $i = 0;
        foreach ($users as $user) {
            $meta = get_user_meta($user->ID);
            echo "<p>".++$i.") ".$user->user_nicename." a.k.a. ".$meta['nickname'][0]."</p>";
        }
    }
}

function create_genres_hierarchical_taxonomy(): void
{
    $labels = array(
        'name' => _x('Genres', 'taxonomy general name'),
        'singular_name' => _x('Genre', 'taxonomy singular name'),
        'search_items' =>  __('Search Genre'),
        'all_items' => __('All Genres'),
        'parent_item' => __('Parent Genre'),
        'parent_item_colon' => __('Parent Genre:'),
        'edit_item' => __('Edit Genre'),
        'update_item' => __('Update Genre'),
        'add_new_item' => __('Add New Genre'),
        'new_item_name' => __('New Genre Name'),
        'menu_name' => __('Genres'),
    );

// Now register the taxonomy
    register_taxonomy('genres', array('Book'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'genre' ),
    ));
}

function pluginSetup(): void
{
    $labels = array(
        'name'                => _x('Book', 'Post Type General Name'),
        'singular_name'       => _x('Book', 'Post Type Singular Name'),
        'menu_name'           => __('Book'),
        'parent_item_colon'   => __('Parent Book'),
        'all_items'           => __('All Books'),
        'view_item'           => __('View Book'),
        'add_new_item'        => __('Add New Book'),
        'add_new'             => __('Add Book'),
        'edit_item'           => __('Edit Book'),
        'update_item'         => __('Update Book'),
        'search_items'        => __('Search Book'),
        'not_found'           => __('Not Found'),
        'not_found_in_trash'  => __('Not found in Trash'),
    );
    $args = array(
        'label'               => __('Book'),
        'description'         => __('Book news and reviews'),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'author', 'custom-fields'),
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,

    );
    create_genres_hierarchical_taxonomy();
    register_post_type('book', $args);
}

add_action('init', 'pluginSetup');
$my_widget = new ListUsersWidget(get_users());

function test_init()
{
    $users = get_users();
    echo "<h1>User List</h1><div>";
    the_widget('ListUsersWidget', $users, []);
    echo "</div>";
}

function printAuthors()
{
    echo "<h1>Author List</h1><div>";
    $args = array(
        'numberposts' => -1,
        'post_type' => 'book'
    );
    $posts = get_posts($args);
    $authors = [];
    foreach ($posts as $post) {
        if (!isset($authors[$post->post_author])) {
            $authors[$post->post_author] = get_user_by('ID', $post->post_author);
        }
    }
    the_widget('ListUsersWidget', $authors, []);
    echo "</div>";
}

function addMenuItem(): void
{
    add_menu_page('Admin Page', 'Check Users', 'manage_options', 'book-plugin', 'test_init');
}

function addSubMenuItem(): void
{
    add_submenu_page(
        'book-plugin',
        'Authors who wrote a book',
        'See Authors',
        'manage_options',
        'authors',
        'printAuthors'
    );
}

function pluginActivate(): void
{
    pluginSetup();
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'pluginActivate');

function pluginDeactivate():void
{
    unregister_post_type('book');
    unregister_taxonomy('genres');
    //remove_submenu_page('book-plugin', 'authors');
    //remove_menu_page('book-plugin');
}

register_deactivation_hook(__FILE__, 'pluginDeactivate');
add_action('admin_menu', 'addMenuItem');
add_action('admin_menu', 'addSubMenuItem');
