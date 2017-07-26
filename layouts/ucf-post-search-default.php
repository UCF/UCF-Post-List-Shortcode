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
			<?php
			$search_data = json_encode( array_map( function( $post ) {
				return $post->post_title;
			}, $posts ) );
			?>
			<div class="ucf-post-search-form" id="post-list-search-<?php echo $atts['list_id']; ?>" data-id="post-list-<?php echo $atts['list_id']; ?>">
				<input class="typeahead" type="text" placeholder="Search">
			</div>
			<script>
			(function() {
				// constructs the suggestion engine
				var typeaheadSource = new Bloodhound({
					datumTokenizer: Bloodhound.tokenizers.whitespace,
					queryTokenizer: Bloodhound.tokenizers.whitespace,
					local: <?php echo $search_data; ?>
				});

				$('#post-list-search-<?php echo $atts['list_id']; ?> .typeahead').typeahead({
					hint: true,
					highlight: true,
					minLength: 2
				},
				{
					source: typeaheadSource
				});
			}());
			</script>
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
