<?php

if ( !function_exists( 'ucf_post_list_search_before' ) ) {

	function ucf_post_list_search_before( $content, $posts, $atts ) {
		ob_start();
	?>
	<div class="ucf-post-search">
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_search_before', 'ucf_post_list_search_before', 10, 3 );

}

if ( !function_exists( 'ucf_post_list_search' ) ) {

	function ucf_post_list_search( $content, $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();
	?>
		<?php if ( $posts ): ?>
			<div class="ucf-post-search-form" id="post-list-search-<?php echo $atts['list_id']; ?>" data-id="post-list-<?php echo $atts['list_id']; ?>">
				<input class="typeahead" type="text" placeholder="<?php echo $atts['search_placeholder']; ?>">
			</div>
		<?php endif; ?>
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_search', 'ucf_post_list_search', 10, 3 );

}

if ( !function_exists( 'ucf_post_list_search_script' ) ) {

	function ucf_post_list_search_script( $content, $posts, $atts, $typeahead_settings ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();
		if ( $posts ):
	?>
		<script class="post-list-search-settings" data-list-id="post-list-<?php echo $atts['list_id']; ?>" type="application/json">
		{
			localdata: <?php echo $typeahead_settings['localdata']; ?>,
			classnames: <?php echo $typeahead_settings['classnames']; ?>,
			limit: <?php echo $typeahead_settings['limit']; ?>,
			templates: <?php echo $typeahead_settings['templates']; ?>
		}
		</script>
	<?php
		endif;

		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_search_script', 'ucf_post_list_search_script', 10, 4 );

}

if ( !function_exists( 'ucf_post_list_search_after' ) ) {

	function ucf_post_list_search_after( $content, $posts, $atts ) {
		ob_start();
	?>
	</div>
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_search_after', 'ucf_post_list_search_after', 10, 3 );

}
