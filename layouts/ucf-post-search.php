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

if ( !function_exists( 'ucf_post_list_search_localdata' ) ) {

	function ucf_post_list_search_get_localdata( $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }

		$retval = array();
		foreach ( $posts as $post ) {
			$retval[] = array(
				'title'   => $post->post_title,
				'link'    => get_permalink( $post->ID ),
				'matches' => array( $post->post_title )
			);
		}

		return json_encode( $retval );
	}

	add_filter( 'ucf_post_list_search_localdata', 'ucf_post_list_search_get_localdata', 10, 2 );
}

if ( !function_exists( 'ucf_post_list_search_script' ) ) {

	function ucf_post_list_search_script( $posts, $atts ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();

		if ( $posts ):

			/**
			 * Returns a local dataset for Bloodhound to search against.
			 * Override this hook to add terms, meta values, etc by which a post can be
			 * searched against.
			 *
			 * @author Jo Dickson
			 * @since 1.0.0
			 * @param $posts array | array of WP Post objects
			 * @param $atts array | array of shortcode attributes
			 * @return string | JSON-encoded array of searchable post data
			**/
			$search_data = apply_filters( 'ucf_post_list_search_localdata', $posts, $atts ) ?: '[]';
	?>
		<script>
		(function() {
			var typeaheadSource = new Bloodhound({
				datumTokenizer: function(datum) {
					return Bloodhound.tokenizers.whitespace(datum.title);
				},
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: <?php echo $search_data; ?>
			});

			$('#post-list-search-<?php echo $atts['list_id']; ?> .typeahead').typeahead(
			{
				hint: false,
				highlight: true,
				minLength: 2
			},
			{
				source: typeaheadSource,
				displayKey: function(obj) {
					return obj.title
				}
			}).on('typeahead:selected', function(event, obj) {
				window.location = obj.link;
			});
		}());
		</script>
	<?php
		endif;

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
