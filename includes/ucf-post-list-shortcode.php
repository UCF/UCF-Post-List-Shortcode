<?php
/**
 * Handles the registration of the UCF Post List Shortcode
 **/

if ( !function_exists( 'sc_ucf_post_list' ) ) {

	function sc_ucf_post_list( $atts, $content='' ) {
		$atts = shortcode_atts( UCF_Post_List_Config::get_option_defaults(), $atts, 'sc_ucf_post_list' );
		$layout = isset( $atts['layout'] ) ? $atts['layout'] : 'default';
		$posts = UCF_Post_List_Common::get_post_list( $atts );

		ob_start();

		echo UCF_Post_List_Common::display_post_list( $posts, $layout, $atts['list_title'] );

		return ob_get_clean(); // Shortcode must *return*!  Do not echo the result!
	}

	add_shortcode( 'ucf-post-list', 'sc_ucf_post_list' );

}

if ( ! function_exists( 'ucf_post_list_shortcode_interface' ) ) {
	function ucf_post_list_shortcode_interface( $shortcodes ) {
		$settings = array(
			'command' => 'ucf-post-list',
			'name'    => 'UCF Post List',
			'desc'    => 'Displays a list of posts. Most WP_Query arguments are supported as shortcode attributes.',
			'fields'  => array(),
			'content' => false
		);

		$shortcodes[] = $settings;

		return $shortcodes;
	}
}
