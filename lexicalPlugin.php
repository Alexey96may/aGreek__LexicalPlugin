<?php

/*
  Plugin Name: Lexical Trainer
  Description: The Lexical Trainer allows creating of trainers to improve the lexical level.
  Author: Alexey Shulga
  Version: 0.0.1
  Author URI: https://github.com/Alexey96may
  License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; //Exit if accessed directly
}

class LexicalPlugin {

  public function register() {
    add_action( "init", [$this, 'customPostType']);
    add_action( "wp_enqueue_scripts", [$this, 'userEnqueue']);
    add_action( "admin_enqueue_scripts", [$this, 'adminEnqueue']);
    //Template Loading
    add_filter( "template_include", [$this, 'trainerTemplate']);

    add_action( "admin_menu", [$this, 'addAdminMenu']);
    //Add links to the Plugin Page
    add_filter( "plugin_action_links_".plugin_basename(__FILE__), [$this, 'add_plugin_setting_link']);
  }

  static function activation() {
    // Update rewrite rules
    flush_rewrite_rules();
  }

  static function deactivation() {
    // Update rewrite rules
    flush_rewrite_rules();
  }

  // static function uninstall() {
  // }

  public function adminEnqueue() {
    wp_enqueue_style( "lexicalTrainerStyle", plugins_url( "/assets/admin/styles.css", __FILE__));
    wp_enqueue_script( "lexicalTrainerScript", plugins_url( "/assets/admin/scripts.js", __FILE__));
  }

  public function userEnqueue() {
    wp_enqueue_style( "lexicalTrainerUserStyle", plugins_url( "/assets/user/styles.css", __FILE__));
    wp_enqueue_script( "lexicalTrainerUserScript", plugins_url( "/assets/user/scripts.js", __FILE__));
  }

  public function customPostType(){
    register_post_type( "trainer_lexica", [
      "public" => true,
      "has_archive" => true,
      "rewrite" => ["slug" => "lexical_trainers"],
      "label" => esc_html__( "Lexical Trainer", "Lexical Plugin" ),
      'supports' => [
        'title', 'editor', 'author', 'thumbnail'
      ],
    ]);
  }

  //Adding of the menu page
  public function addAdminMenu(){
    add_menu_page(
      esc_html__( 'Lexica', 'lexical trainer' ),
      esc_html__( 'Lexical Tools', 'lexical trainer' ),
      'manage_options',
      'lexical_tools',
      [$this, 'adminLexicalToolsMenu'],
      'dashicons-admin-generic',
      40
    );
  }

  //Add the admin page for Lexical Tools
  public function adminLexicalToolsMenu(){
    require_once plugin_dir_path(__FILE__) . 'admin/lexicalToolsPage.php';
  }

  public function add_plugin_setting_link($links){
    $myLink = '<a href="admin.php?page=lexical_tools">'. esc_html__('Setting', 'lexical trainer') . '</a>';
    array_push($links, $myLink);
    return $links;
  }

  public function trainerTemplate($template) {
    if (is_post_type_archive("trainer_lexica")) {
      $themeFiles = ['archive-trainer_lexica.php', 'lexical_trainer/archive-trainer_lexica.php'];
      $isExistInTheme = locate_template($themeFiles, false);

      if ($isExistInTheme !=='') {
        return $isExistInTheme;
      } else {
        return plugin_dir_path(__FILE__) . 'templates/archive-trainer_lexica.php';
      }
    }
    return $template;
  }

}

if (class_exists(LexicalPlugin)) {
  $lexPlugin = new LexicalPlugin();
  $lexPlugin->register();
} else {
  die;
}

register_activation_hook( __FILE__, array( $lexPlugin, 'activation' ) );
register_deactivation_hook( __FILE__, array( $lexPlugin, 'deactivation' ) );
// register_uninstall_hook( __FILE__, array( $lexPlugin, 'uninstall' ) );