<?php
/*
Plugin Name: UCF Post List Shortcode
Description: Provides a shortcode for displaying lists of posts.
Version: 1.0.0
Author: UCF Web Communications
License: GPL3
*/
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'UCF_POST_LIST__PLUGIN_FILE', __FILE__ );

require_once 'includes/ucf-post-list-config.php';
require_once 'includes/ucf-post-list-common.php';
require_once 'includes/ucf-post-list-shortcode.php';
require_once 'admin/ucf-post-list-admin.php';

require_once 'layouts/ucf-post-list-default.php';
require_once 'layouts/ucf-post-list-card.php';


/**
 * Activation/deactivation hooks
 **/
if ( !function_exists( 'ucf_post_list_plugin_activation' ) ) {
	function ucf_post_list_plugin_activation() {
		UCF_Post_List_Config::add_configurable_options();
		flush_rewrite_rules();
	}
}

if ( !function_exists( 'ucf_post_list_plugin_deactivation' ) ) {
	function ucf_post_list_plugin_deactivation() {
		flush_rewrite_rules();
	}
}

register_activation_hook( UCF_POST_LIST__PLUGIN_FILE, 'ucf_post_list_plugin_activation' );
register_deactivation_hook( UCF_POST_LIST__PLUGIN_FILE, 'ucf_post_list_plugin_deactivation' );


/**
 * Plugin-dependent actions:
 **/
if ( ! function_exists( 'ucf_post_list_init' ) ) {
	function ucf_post_list_init() {
		// If the `WP-Shortcode-Interface` plugin is installed, add the shortcode
		// definitions.
		if ( class_exists( 'WP_SCIF_Config' ) ) {
			add_filter( 'wp_scif_add_shortcode', 'ucf_post_list_shortcode_interface', 10, 1 );
		}
	}
	add_action( 'plugins_loaded', 'ucf_post_list_init' );
}
