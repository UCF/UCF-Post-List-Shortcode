<?php
if ( !function_exists( 'ucf_post_list_search_before' ) ) {

	function ucf_post_list_search_before( $posts, $atts ) {
		ob_start();
	?>
	<div class="ucf-post-search">
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_search_before', 'ucf_post_list_search_before', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_search' ) ) {

	function ucf_post_list_search( $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();
	?>
		<?php if ( $posts ): ?>
			<div class="ucf-post-search-form" id="post-list-search-<?php echo $atts['list_id']; ?>" data-id="post-list-<?php echo $atts['list_id']; ?>">
				<input class="typeahead" type="text" placeholder="Search">
			</div>
		<?php endif; ?>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_search', 'ucf_post_list_search', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_search_script' ) ) {

	function ucf_post_list_search_script( $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();
	?>
		<?php if ( $posts ): ?>
			<?php
			// TODO add post permalinks
			// TODO make searchable strings for each post an array
			$search_data = json_encode( array_map( function( $post ) {
				return $post->post_title;
			}, $posts ) );
			?>
			<script>
			(function() {
				// TODO add selection onclick event to navigate to post permalink
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

	add_action( 'ucf_post_list_search_script', 'ucf_post_list_search_script', 10, 2 );

}

if ( !function_exists( 'ucf_post_list_search_after' ) ) {

	function ucf_post_list_search_after( $posts, $atts ) {
		ob_start();
	?>
	</div>
	<?php
		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_search_after', 'ucf_post_list_search_after', 10, 2 );

}
