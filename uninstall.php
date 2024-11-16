<?php

if(!defined('WP_UNINSTALL_PLUGIN')){
    die;
}

//Delete post type from db

//global $wpdb;
//$wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type IN ('room');");

//remove meta

//remove tax/terms

//remove comments

$trainers = get_posts(['post_type' => 'trainer_lexica', 'numberposts' => -1]);
foreach($trainers as $trainer){
    wp_delete_post($trainer->ID, true);
}