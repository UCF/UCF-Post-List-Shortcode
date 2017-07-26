<?php
if ( !function_exists( 'ucf_post_list_display_default_before' ) ) {

	function ucf_post_list_display_default_before( $posts, $atts ) {
		ob_start();
	?>
	<div class="ucf-post-list ucf-post-list-default" id="post-list-<?php echo $atts['list_id']; ?>">
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_default_before', 'ucf_post_list_display_default_before', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_display_default_title' ) ) {

	function ucf_post_list_display_default_title( $posts, $atts ) {
		$formatted_title = '';

		if ( $list_title = $atts['list_title'] ) {
			$formatted_title = '<h2 class="ucf-post-list-title">' . $list_title . '</h2>';
		}

		echo $formatted_title;
	}

	add_action( 'ucf_post_list_display_default_title', 'ucf_post_list_display_default_title', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_display_default' ) ) {

	function ucf_post_list_display_default( $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();
	?>
		<?php if ( $posts ): ?>
			<?php
			if ( $atts['display_search'] ) {
				echo UCF_Post_List_Common::display_post_search( $posts, $atts['search_layout'], $atts );
			}
			?>
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
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_default', 'ucf_post_list_display_default', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_display_default_after' ) ) {

	function ucf_post_list_display_default_after( $posts, $atts ) {
		ob_start();
	?>
	</div>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_default_after', 'ucf_post_list_display_default_after', 10, 2 );

}
