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
  public function __construct(){
    add_action( "init", [$this, customPostType]);
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

  public function customPostType(){
    register_post_type( "trainer_lexica", [
      "public" => true,
      "label" => esc_html__( "Lexical Trainer", "Lexical Plugin" ),
      'supports' => [
        'title', 'editor', 'author', 'thumbnail'
      ],
    ]);
  }
}

if (class_exists(LexicalPlugin)) {
  $lexPlugin = new LexicalPlugin();
} else {
  die;
}

register_activation_hook( __FILE__, array( $lexPlugin, 'activation' ) );
register_deactivation_hook( __FILE__, array( $lexPlugin, 'deactivation' ) );
// register_uninstall_hook( __FILE__, array( $lexPlugin, 'uninstall' ) );