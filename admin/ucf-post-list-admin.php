<?php
/**
 * Handles admin actions
 **/
if ( ! class_exists( 'UCF_Post_List_Admin' ) ) {
	class UCF_Post_List_Admin {
		public static function enqueue_admin_scripts() {
			if ( is_admin() ) {
				$plugin_data = get_plugin_data( UCF_POST_LIST__PLUGIN_FILE, false, false );
				$version     = $plugin_data['Version'];

				if ( function_exists( 'wp_enqueue_media' ) ) {
					wp_enqueue_media();
				} else {
					wp_enqueue_style( 'thickbox' );
					wp_enqueue_script( 'media-upload' );
					wp_enqueue_script( 'thickbox' );
					wp_enqueue_media();
				}
				wp_enqueue_script( 'ucf-post-list-admin', plugins_url( 'static/js/ucf-post-list-admin.min.js', UCF_POST_LIST__PLUGIN_FILE ), array(), $version, true );
			}
		}
	}
	add_action( 'admin_enqueue_scripts', array( 'UCF_Post_List_Admin', 'enqueue_admin_scripts' ) );
}
?>
