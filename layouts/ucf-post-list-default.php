<?php
if ( !function_exists( 'ucf_post_list_display_default_before' ) ) {

	function ucf_post_list_display_default_before( $content, $posts, $atts ) {
		ob_start();
	?>
	<div class="ucf-post-list ucf-post-list-default" id="post-list-<?php echo $atts['list_id']; ?>">
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_default_before', 'ucf_post_list_display_default_before', 10, 3 );

}

if ( !function_exists( 'ucf_post_list_display_default_title' ) ) {

	function ucf_post_list_display_default_title( $content, $posts, $atts ) {
		$formatted_title = '';

		if ( $list_title = $atts['list_title'] ) {
			$formatted_title = '<h2 class="ucf-post-list-title">' . $list_title . '</h2>';
		}

		return $formatted_title;
	}

	add_filter( 'ucf_post_list_display_default_title', 'ucf_post_list_display_default_title', 10, 3 );

}

if ( !function_exists( 'ucf_post_list_display_default' ) ) {

	function ucf_post_list_display_default( $content, $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();
	?>
		<?php if ( $posts ): ?>
			<ul class="ucf-post-list-items">
				<?php foreach ( $posts as $item ): ?>
				<li class="ucf-post-list-item">
					<a href="<?php echo get_permalink( $item->ID ); ?>"><?php echo $item->post_title; ?></a>
				</li>
				<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<div class="ucf-post-list-error">No results found.</div>
		<?php endif; ?>
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_default', 'ucf_post_list_display_default', 10, 3 );

}

if ( !function_exists( 'ucf_post_list_display_default_after' ) ) {

	function ucf_post_list_display_default_after( $content, $posts, $atts ) {
		ob_start();
	?>
	</div>
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_default_after', 'ucf_post_list_display_default_after', 10, 3 );

}
