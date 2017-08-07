<?php
/*
Plugin Name: LikeHack Widget
Description: LikeHack plugin for WordPress
Version: 2.2
Author: Likehack Crew
Author URI: http://likehack.com
*/

define('LIKEHACK_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('LIKEHACK_PLUGIN_DIR', basename(LIKEHACK_PLUGIN_PATH));
define('LIKEHACK_PLUGIN_URL', plugin_dir_url(__FILE__));

include_once LIKEHACK_PLUGIN_PATH . '/includes/likehack.class.php';
include_once LIKEHACK_PLUGIN_PATH . '/includes/likehack-widget.class.php';

$LikeHack = new LikeHack();

register_activation_hook(__FILE__, array($LikeHack, 'on_plugin_activate'));
register_deactivation_hook(__FILE__, array($LikeHack, 'on_plugin_deactivate'));