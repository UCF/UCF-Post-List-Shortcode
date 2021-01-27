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
			<div class="ucf-post-search-form" data-id="post-list-<?php echo $atts['list_id']; ?>">
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

		if ( $posts ) {
			// Enqueue JS:
			$post_list_search_settings_dep = 'ucf-post-list-js';

			if ( wp_script_is( 'ucf-post-list-typeahead-js', 'registered' ) ) {
				wp_enqueue_script( 'ucf-post-list-typeahead-js' );
			}
			if ( wp_script_is( 'ucf-post-list-handlebars-js', 'registered' ) ) {
				wp_enqueue_script( 'ucf-post-list-handlebars-js' );
			}

			if ( ! wp_script_is( 'ucf-post-list-js', 'registered' ) ) {
				$post_list_search_settings_dep = apply_filters( 'ucf_post_list_search_settings_dep', $post_list_search_settings_dep );
			}

			// Generate inline script that initializes the search
			// with provided $typeahead_settings:
			$post_list_search_settings = '';
			ob_start();
		?>
			(function($) {
				$('.ucf-post-search-form[data-id="post-list-<?php echo $atts['list_id']; ?>"] .typeahead')
					.UCFPostListSearch({
						localdata: <?php echo $typeahead_settings['localdata']; ?>,
						classnames: <?php echo $typeahead_settings['classnames']; ?>,
						limit: <?php echo $typeahead_settings['limit']; ?>,
						templates: <?php echo $typeahead_settings['templates']; ?>
					});
			}(jQuery));
		<?php
			$post_list_search_settings = trim( ob_get_clean() );

			// Enqueue inline init script:
			wp_add_inline_script( $post_list_search_settings_dep, $post_list_search_settings );

			// Enqueue post list JS:
			if ( wp_script_is( 'ucf-post-list-js', 'registered' ) ) {
				wp_enqueue_script( 'ucf-post-list-js' );
			}
		}

		return '';
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
