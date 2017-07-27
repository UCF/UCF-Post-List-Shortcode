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

	function ucf_post_list_search_script( $posts, $atts, $typeahead_settings ) {
		if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
		ob_start();

		if ( $posts ):
	?>
		<script>
		(function() {
			var typeaheadSource = new Bloodhound({
				datumTokenizer: function(datum) {
					return Bloodhound.tokenizers.whitespace(datum.matches);
				},
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: <?php echo $typeahead_settings['localdata']; ?>
			});

			$('#post-list-search-<?php echo $atts['list_id']; ?> .typeahead').typeahead(
			{
				hint: false,
				highlight: true,
				minLength: 2,
				classNames: <?php echo $typeahead_settings['classnames']; ?>
			},
			{
				source: typeaheadSource,
				limit: <?php echo $typeahead_settings['limit']; ?>,
				displayKey: function(obj) {
					return obj.title
				},
				templates: <?php echo $typeahead_settings['templates']; ?>
			}).on('typeahead:selected', function(event, obj) {
				window.location = obj.link;
			});
		}());
		</script>
	<?php
		endif;

		echo ob_get_clean();
	}

	add_action( 'ucf_post_list_search_script', 'ucf_post_list_search_script', 10, 3 );

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
