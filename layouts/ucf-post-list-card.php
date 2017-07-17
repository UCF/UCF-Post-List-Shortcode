<?php
/**
 * The default functions for the card layout
 **/
if ( ! function_exists( 'ucf_post_list_display_card_before' ) ) {
	function ucf_post_list_display_card_before( $items, $title ) {
		ob_start();
	?>
		<div class="ucf-post-list card-layout">
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_card_before', 'ucf_post_list_display_card_before', 10, 2 );
}


if ( !function_exists( 'ucf_post_list_display_card_title' ) ) {

	function ucf_post_list_display_card_title( $items, $title ) {

		if ( $title ) {
			$formatted_title = '<h2 class="ucf-post-list-title">' . $title . '</h2>';
		}

		echo $formatted_title;
	}

	add_action( 'ucf_post_list_display_card_title', 'ucf_post_list_display_card_title', 10, 2 );

}

if ( ! function_exists( 'ucf_post_list_display_card' ) ) {
	function ucf_post_list_display_card( $items, $title, $show_image, $posts_per_row ) {
		if ( ! is_array( $items ) ) { $items = array( $items ); }
		ob_start();
?>
		<?php if ( $items ): ?>
		<div class="ucf-post-list-card-deck">

		<?php foreach( $items as $index=>$item ) :
			$date = date("M d",strtotime($item->post_date));
			if( $show_image ) {
				$item_img = UCF_POST_LIST_Common::get_image_or_fallback( $item );
			}

			if( $posts_per_row > 0 && $index !== 0 && ( $index % $posts_per_row ) === 0 ) {
				echo '</div><div class="ucf-post-list-card-deck">';
			}
		?>
		<div class="ucf-post-list-card">
			<a href="<?php echo $item->guid; ?>">
				<?php if( $show_image && $item_img ) : ?>
					<img src="<?php echo $item_img; ?>" class="ucf-post-list-thumbnail-image" alt="<?php echo $item->post_title; ?>">
				<?php endif; ?>
				<div class="ucf-post-list-card-block">
					<h3 class="ucf-post-list-card-title"><?php echo $item->post_title; ?></h3>
					<p class="ucf-post-list-card-text"><?php echo $date; ?></p>
				</div>
			</a>
		</div>
		<?php endforeach; ?>

		<?php else: ?>
		<div class="ucf-post-list-error">No results found.</div>
		<?php endif;

		echo '</div>';

		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_card', 'ucf_post_list_display_card', 10, 4 );
}

if ( ! function_exists( 'ucf_post_list_display_card_after' ) ) {
	function ucf_post_list_display_card_after( $items, $title ) {
		ob_start();
	?>
		</div>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_display_card_after', 'ucf_post_list_display_card_after', 10, 2 );
}
