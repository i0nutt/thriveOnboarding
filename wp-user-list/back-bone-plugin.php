<?php
require_once(__DIR__ . './src/Book.php');
/**
 * @link              http://ionut.local/wp-admin/admin.php?page=back-bone-plugin
 * @since             1.0
 * @package           Back Bone
 *
 * @wordpress-plugin
 * Plugin Name:       WP Back Bone
 * Description:       A little widget to manage users.
 * Version:           1.0
 * Author:            Ionut .T
 * Network:           False
 */


// Create an instance of the Plugin Class
function call_wp_user_list(): Book
{
    return new Book();
}

if (! function_exists('pp')) {
    function pp(): string
    {
        return plugin_dir_url(__FILE__);
    }
}
function pluginActivate(): void
{
    flush_rewrite_rules();
}

function pluginDeactivate():void
{
    unregister_post_type('book');
    unregister_taxonomy('genres');
    //remove_submenu_page('book-plugin', 'authors');
    //remove_menu_page('book-plugin');
}

function addJS(): void
{
}

//add_action('the_content', 'addJS');
//do_action('wp_enqueue_scripts');
//add_action('init', 'call_wp_user_list');
//register_activation_hook(pp(), 'pluginActivate');
register_deactivation_hook(pp(), 'pluginDeactivate');
