<?php
if ( !function_exists( 'ucf_post_list_display_default_before' ) ) {

	function ucf_post_list_display_default_before( $items, $title ) {
		ob_start();
	?>
	<div class="ucf-post-list ucf-post-list-default">
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

	function ucf_post_list_display_default( $items, $title, $show_image, $posts_per_row ) {
		if ( ! is_array( $items ) && $items !== false ) { $items = array( $items ); }
		ob_start();
	?>
		<?php if ( $items ): ?>
		<ul class="ucf-post-list-items">
			<?php foreach ( $items as $item ): ?>
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

	add_action( 'ucf_post_list_display_default', 'ucf_post_list_display_default', 10, 4 );

}

if ( !function_exists( 'ucf_post_list_display_default_after' ) ) {

	function ucf_post_list_display_default_after( $items, $title ) {
		ob_start();
	?>
	</div>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_default_after', 'ucf_post_list_display_default_after', 10, 2 );

}
