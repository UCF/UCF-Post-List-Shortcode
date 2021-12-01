<?php
/*
Plugin Name: UCF Post List Shortcode
Description: Provides a shortcode for displaying lists of posts.
Version: 2.1.0
Author: UCF Web Communications
License: GPL3
Github Plugin URI: UCF/UCF-Post-List-Shortcode
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'UCF_POST_LIST__PLUGIN_FILE', __FILE__ );
define( 'UCF_POST_LIST__PLUGIN_URL', plugins_url( basename( dirname( __FILE__ ) ) ) );
define( 'UCF_POST_LIST__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UCF_POST_LIST__STATIC_URL', UCF_POST_LIST__PLUGIN_URL . '/static' );
define( 'UCF_POST_LIST__STYLES_URL', UCF_POST_LIST__STATIC_URL . '/css' );
define( 'UCF_POST_LIST__SCRIPT_URL', UCF_POST_LIST__STATIC_URL . '/js' );
define( 'UCF_POST_LIST__TYPEAHEAD', 'https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.0.1/typeahead.bundle.min.js' );
define( 'UCF_POST_LIST__HANDLEBARS', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js' );

require_once UCF_POST_LIST__PLUGIN_DIR . 'includes/ucf-post-list-config.php';
require_once UCF_POST_LIST__PLUGIN_DIR . 'includes/ucf-post-list-common.php';
require_once UCF_POST_LIST__PLUGIN_DIR . 'includes/ucf-post-list-shortcode.php';
require_once UCF_POST_LIST__PLUGIN_DIR . 'admin/ucf-post-list-admin.php';

require_once UCF_POST_LIST__PLUGIN_DIR . 'layouts/ucf-post-list-default.php';
require_once UCF_POST_LIST__PLUGIN_DIR . 'layouts/ucf-post-list-card.php';
require_once UCF_POST_LIST__PLUGIN_DIR . 'layouts/ucf-post-list-count.php';
require_once UCF_POST_LIST__PLUGIN_DIR . 'layouts/ucf-post-search.php';


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
