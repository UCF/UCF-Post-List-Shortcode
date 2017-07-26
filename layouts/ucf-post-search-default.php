<?php
if ( !function_exists( 'ucf_post_list_search_default_before' ) ) {

	function ucf_post_list_search_default_before( $posts, $list_title ) {
		ob_start();
	?>
	<div class="ucf-post-search ucf-post-search-default">
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_search_default_before', 'ucf_post_list_search_default_before', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_search_default' ) ) {

	function ucf_post_list_search_default( $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();

		// TODO update this--might need a different set of layout hooks for search stuff
	?>
		<?php if ( $posts ): ?>
			<ul class="ucf-post-search-items">
				<?php foreach ( $posts as $item ): ?>
				<li class="ucf-post-search-item">
					<a href="<?php echo get_permalink( $item->ID ); ?>"><?php echo $item->post_title; ?></a>
				</li>
				<?php endforeach; ?>
			</ul>
		<?php else: ?>
		<div class="ucf-post-search-error">No results found.</div>
		<?php endif; ?>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_search_default', 'ucf_post_list_search_default', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_search_default_after' ) ) {

	function ucf_post_list_search_default_after( $posts, $list_title ) {
		ob_start();
	?>
	</div>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_search_default_after', 'ucf_post_list_search_default_after', 10, 2 );

}
