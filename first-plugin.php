
<?php

/**
* Plugin Name: filter title
* Author: Ionut T.
*/
//appends the author to the title
function addAuthor($title): string
{
    return $title.' by '.get_the_author();
}

add_filter('the_title', 'addAuthor');

function addComment($new_status, $old_status, $post):void
{
    if ($new_status == 'publish' && $old_status != 'publish') {
        $current_user = wp_get_current_user();
        $comment      = "Comment action3";

        $time = current_time('mysql');

        $data = array(
            'comment_post_ID'      => $post->ID,
            'comment_author'       => get_the_author(),
            'comment_author_email' => 'ionut.tiplea@bitstone.eu',
            'comment_author_url'   => 'http://ionut.local/wp-admin/profile.php',
            'comment_content'      => $comment,
            'user_id'              => $current_user->ID,
            'comment_date'         => $time,
            'comment_approved'     => 1,
            'comment_type'         => 'custom-comment-class'
        );
        wp_new_comment($data);
    }
}

add_action('transition_post_status', 'addComment', 10, 3);
