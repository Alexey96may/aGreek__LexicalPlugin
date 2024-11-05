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
  function __construct($string){
    echo "Hello $string";
  }
}

if (class_exists(LexicalPlugin)) {
  $plugin = new LexicalPlugin("user");
} else {
  die;
}