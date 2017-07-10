<?php
/**
 * Place common functions here.
 **/

if ( !class_exists( 'UCF_Post_List_Common' ) ) {

	class UCF_Post_List_Common {
		public static function display_post_list( $items, $layout, $title ) {
			if ( has_action( 'ucf_post_list_display_' . $layout . '_before' ) ) {
				do_action( 'ucf_post_list_display_' . $layout . '_before', $items, $title );
			}

			if ( has_action( 'ucf_post_list_display_' . $layout . '_title'  ) ) {
				do_action( 'ucf_post_list_display_' . $layout . '_title', $items, $title );
			}

			if ( has_action( 'ucf_post_list_display_' . $layout  ) ) {
				do_action( 'ucf_post_list_display_' . $layout, $items, $title );
			}

			if ( has_action( 'ucf_post_list_display_' . $layout . '_after' ) ) {
				do_action( 'ucf_post_list_display_' . $layout . '_after', $items, $title );
			}
		}

		public static function get_post_list( $args ) {
			// TODO
			var_dump($args);
			echo '<br><br>';
			var_dump(self::prepare_post_list_args( $args ));
			echo '<br><br><br>';
			return get_posts( self::prepare_post_list_args( $args ) );
		}

		public static function prepare_post_list_args( $args ) {
			// TODO: meta_value support for ACF relationship fields
			return array_filter( $args, array( 'UCF_Post_List_Common', 'filter_post_list_arg' ) );
		}

		/**
		 * Removes empty arguments while preserving 0 value integers.
		 **/
		private static function filter_post_list_arg( $arg ) {
			return !(
				is_array( $arg ) && empty( $arg )
				|| is_null( $arg )
				|| is_string( $arg ) && empty( $arg )
			);
		}
	}
}

if ( !function_exists( 'ucf_post_list_display_default_before' ) ) {

	function ucf_post_list_display_default_before( $items, $title ) {
		ob_start();
	?>
	<!-- TODO -->
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_default_before', 'ucf_post_list_display_default_before', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_display_default_title' ) ) {

	function ucf_post_list_display_default_title( $items, $title ) {
		$formatted_title = '';

		if ( $title ) {
			$formatted_title = '<h2 class="ucf-post-list-title">' . $title . '</h2>';
		}

		echo $formatted_title;
	}

	add_action( 'ucf_post_list_display_default_title', 'ucf_post_list_display_default_title', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_display_default' ) ) {

	function ucf_post_list_display_default( $items, $title ) {
		if ( ! is_array( $items ) ) { $items = array( $items ); }
		ob_start();
	?>
	<!-- TODO -->
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_default', 'ucf_post_list_display_default', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_display_default_after' ) ) {

	function ucf_post_list_display_default_after( $items, $title ) {
		ob_start();
	?>
	<!-- TODO -->
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_default_after', 'ucf_post_list_display_default_after', 10, 2 );

}

if ( ! function_exists( 'ucf_post_list_enqueue_assets' ) ) {
	function ucf_post_list_enqueue_assets() {
		// CSS
		$include_css = UCF_Post_List_Config::get_option_or_default( 'include_css' );
		$css_deps = apply_filters( 'ucf_post_list_style_deps', array() );

		if ( $include_css ) {
			wp_enqueue_style( 'ucf_post_list_css', plugins_url( 'static/css/ucf-post-list.min.css', UCF_POST_LIST__PLUGIN_FILE ), $css_deps, false, 'screen' );
		}
	}

	add_action( 'wp_enqueue_scripts', 'ucf_post_list_enqueue_assets' );
}
