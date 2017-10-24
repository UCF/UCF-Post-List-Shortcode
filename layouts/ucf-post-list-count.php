<?php
if ( !function_exists( 'ucf_post_list_display_count_before' ) ) {

	function ucf_post_list_display_count_before( $content, $posts, $atts ) {
		ob_start();
	?>
	<span class="ucf-post-list ucf-post-list-count" id="post-list-<?php echo $atts['list_id']; ?>">
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_count_before', 'ucf_post_list_display_count_before', 10, 3 );

}

if ( !function_exists( 'ucf_post_list_display_count' ) ) {

	function ucf_post_list_display_count( $content, $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();
		echo count( $posts );
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_count', 'ucf_post_list_display_count', 10, 3 );

}

if ( !function_exists( 'ucf_post_list_display_count_after' ) ) {

	function ucf_post_list_display_count_after( $content, $posts, $atts ) {
		ob_start();
	?>
	</span>
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_count_after', 'ucf_post_list_display_count_after', 10, 3 );

}
