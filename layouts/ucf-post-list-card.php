<?php

/**
 * The default functions for the card layout
 **/

if ( ! function_exists( 'ucf_post_list_display_card_before' ) ) {
	function ucf_post_list_display_card_before( $content, $posts, $atts ) {
		ob_start();
	?>
		<div class="ucf-post-list card-layout" id="post-list-<?php echo $atts['list_id']; ?>">
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_card_before', 'ucf_post_list_display_card_before', 10, 3 );
}


if ( !function_exists( 'ucf_post_list_display_card_title' ) ) {

	function ucf_post_list_display_card_title( $content, $posts, $atts ) {
		$formatted_title = '';

		if ( $list_title = $atts['list_title'] ) {
			$formatted_title = '<h2 class="ucf-post-list-title">' . $list_title . '</h2>';
		}

		return $formatted_title;
	}

	add_filter( 'ucf_post_list_display_card_title', 'ucf_post_list_display_card_title', 10, 3 );

}

if ( ! function_exists( 'ucf_post_list_display_card' ) ) {
	function ucf_post_list_display_card( $content, $posts, $atts ) {
		if ( $posts && ! is_array( $posts ) ) { $posts = array( $posts ); }
		ob_start();
?>
		<?php if ( $posts ): ?>
			<div class="ucf-post-list-card-deck">

			<?php
			foreach( $posts as $index=>$item ) :
				$date = date( "M d", strtotime( $item->post_date ) );
				if( $atts['show_image'] ) {
					$item_img        = UCF_Post_List_Common::get_image_or_fallback( $item );
					$item_img_srcset = UCF_Post_List_Common::get_image_srcset( $item );
				}

				if( $atts['posts_per_row'] > 0 && $index !== 0 && ( $index % $atts['posts_per_row'] ) === 0 ) {
					echo '</div><div class="ucf-post-list-card-deck">';
				}

				if( $atts['show_excerpt'] ) {
					$char_limit = $atts['excerpt_length'];
					$item_excerpt	 = UCF_Post_List_Common::get_excerpt( $item, $char_limit );
				}
			?>
				<div class="ucf-post-list-card">
					<a class="ucf-post-list-card-link" href="<?php echo get_permalink( $item->ID ); ?>">
						<?php if( $atts['show_image'] && $item_img ) : ?>
							<img src="<?php echo $item_img; ?>" srcset="<?php echo $item_img_srcset; ?>" class="ucf-post-list-thumbnail-image" alt="<?php echo $item->post_title; ?>">
						<?php endif; ?>
						<div class="ucf-post-list-card-block">
							<h3 class="ucf-post-list-card-title"><?php echo $item->post_title; ?></h3>
							<?php if( $atts['show_excerpt'] ) : ?>
								<div class="ucf-post-list-excerpt-text ucf-post-list-card-text"><?php echo $item_excerpt; ?></div>
							<?php endif; ?>
							<p class="ucf-post-list-card-text"><?php echo $date; ?></p>
						</div>
					</a>
				</div>
			<?php endforeach; ?>

			</div>

		<?php else: ?>
			<div class="ucf-post-list-error">No results found.</div>
		<?php endif;

		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_card', 'ucf_post_list_display_card', 10, 3 );
}

if ( ! function_exists( 'ucf_post_list_display_card_after' ) ) {
	function ucf_post_list_display_card_after( $content, $posts, $atts ) {
		ob_start();
	?>
		</div>
	<?php
		return ob_get_clean();
	}

	add_filter( 'ucf_post_list_display_card_after', 'ucf_post_list_display_card_after', 10, 3 );
}

