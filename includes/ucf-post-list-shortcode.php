<?php
/**
 * Handles the registration of the UCF Post List Shortcode
 **/

if ( !function_exists( 'sc_ucf_post_list' ) ) {

	/**
	 * Callback function for the ucf-post-list shortcode.
	 *
	 * @author Jo Dickson
	 * @since 1.0.0
	 * @param $atts array | shortcode attributes
	 * @param $content string | content between shortcode brackets
	 * @return string | post list HTML markup
	 **/
	function sc_ucf_post_list( $atts, $content='' ) {
		$atts = shortcode_atts( UCF_Post_List_Config::get_shortcode_atts(), $atts, 'ucf-post-list' );
		$layout = isset( $atts['layout'] ) ? $atts['layout'] : 'default';
		$posts = UCF_Post_List_Common::get_post_list( $atts );

		ob_start();

		echo UCF_Post_List_Common::display_post_list( $posts, $layout, $atts );

		return ob_get_clean(); // Shortcode must *return*!  Do not echo the result!
	}

	add_shortcode( 'ucf-post-list', 'sc_ucf_post_list' );

}

if ( ! function_exists( 'ucf_post_list_shortcode_interface' ) ) {

	/**
	 * Adds the ucf-post-list shortcode to the WP-SCIF plugin's shortcode list.
	 *
	 * @author Jo Dickson
	 * @since 1.0.0
	 * @param $shortcodes array | array of registered shortcodes
	 * @return array | array of registered shortcodes
	 **/
	function ucf_post_list_shortcode_interface( $shortcodes ) {
		$settings = array(
			'command' => 'ucf-post-list',
			'name'    => 'UCF Post List',
			'desc'    => 'Displays a list of posts. Most WP_Query arguments are supported as shortcode attributes.',
			'fields'  => array(),
			'content' => false,
			'preview' => false
		);

		$shortcodes[] = $settings;

		return $shortcodes;
	}

}
