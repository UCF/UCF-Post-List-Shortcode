<?php
/**
 * Place common functions here.
 **/

if ( !class_exists( 'UCF_Post_List_Common' ) ) {

	class UCF_Post_List_Common {

		/**
		 * Returns full markup for a post list search.
		 *
		 * @author RJ Bruneel, Jo Dickson
		 * @since 1.0.0
		 * @param $posts Mixed | array of WP Post objects or false
		 * @return string | post list HTML string
		 **/
		public static function display_post_search( $posts, $layout, $atts ) {
			ob_start();

			if ( has_action( 'ucf_post_list_search_' . $layout . '_before' ) ) {
				do_action( 'ucf_post_list_search_' . $layout . '_before', $posts, $atts );
			}

			if ( has_action( 'ucf_post_list_search_' . $layout  ) ) {
				do_action( 'ucf_post_list_search_' . $layout, $posts, $atts );
			}

			if ( has_action( 'ucf_post_list_search_' . $layout . '_after' ) ) {
				do_action( 'ucf_post_list_search_' . $layout . '_after', $posts, $atts );
			}

			return ob_get_clean();
		}


		/**
		 * Returns full markup for a list of posts.
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 * @param $posts Mixed | array of WP Post objects or false
		 * @param $layout string | layout name
		 * @param $atts array | array of options
		 * @return string | post list HTML string
		 **/
		public static function display_post_list( $posts, $layout, $atts ) {
			ob_start();

			if ( has_action( 'ucf_post_list_display_' . $layout . '_before' ) ) {
				do_action( 'ucf_post_list_display_' . $layout . '_before', $posts, $atts );
			}

			if ( has_action( 'ucf_post_list_display_' . $layout . '_title'  ) ) {
				do_action( 'ucf_post_list_display_' . $layout . '_title', $posts, $atts );
			}

			if ( has_action( 'ucf_post_list_display_' . $layout  ) ) {
				do_action( 'ucf_post_list_display_' . $layout, $posts, $atts );
			}

			if ( has_action( 'ucf_post_list_display_' . $layout . '_after' ) ) {
				do_action( 'ucf_post_list_display_' . $layout . '_after', $posts, $atts );
			}

			return ob_get_clean();
		}

		/**
		 * Retrieves the post featured or fallback image
		 *
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $item object | object containing the WP Post
		 * @return string | image url
		 **/
		public static function get_image_or_fallback( $item ) {
			$item_img = wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'single-post-thumbnail' );
			$item_img = $item_img ? $item_img[0] : null;
			if( $item_img === null ) {
				$item_img = wp_get_attachment_url( UCF_Post_List_Config::get_option_or_default( 'ucf_post_list_fallback_image' ));
			}
			return $item_img;
		}


		/**
		 * Retrieves a list of WP Post objects, using arguments passed in
		 * via $args.
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 * @param $args array | array of post query arguments, from post list shortcode
		 * @return Mixed | array of WP Post objects, or false on failure
		 **/
		public static function get_post_list( $args ) {
			$filtered_args = self::prepare_post_list_args( $args );
			return is_array( $filtered_args ) ? get_posts( $filtered_args ) : false;
		}

		/**
		 * Additional massaging of arguments before passing them to
		 * get_posts().
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 * @param $args array | array of post query arguments, from post list shortcode
		 * @return array | array of filtered post query arguments
		 **/
		public static function prepare_post_list_args( $args ) {
			// We intentionally remove empty values before passing them to
		 	// get_posts() to allow WP to set its own defaults as necessary (so
		 	// that we don't have to add/maintain them in this plugin.)
			$filtered_args = array_filter( $args, array( 'UCF_Post_List_Common', 'not_empty_allow_zero' ) );

			// Handle taxonomy queries
			$filtered_args = self::filter_taxonomy_post_list_args( $filtered_args );

			// If Advanced Custom Fields is enabled, provide support for
			// relationship fields.
			if ( class_exists( 'ACF' ) ) {
				$filtered_args = self::filter_acf_relationship_field_meta( $filtered_args );
			}

			return $filtered_args;
		}

		/**
		 * Returns whether or not the value is empty, while allowing 0 values.
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 * @param $arg Mixed | Any single post list argument
		 * @return boolean | True if the value is not empty, or False if it is empty
		 **/
		private static function not_empty_allow_zero( $arg ) {
			return !(
				is_array( $arg ) && empty( $arg )
				|| is_null( $arg )
				|| is_string( $arg ) && empty( $arg )
			);
		}

		/**
		 * Given an array of post list arguments that have already been
		 * filtered to remove empty non-zero values, this function
		 * replaces custom taxonomy arguments with a proper tax_query.
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 * @param $args array | array of post query arguments, from post list shortcode
		 * @return array | array of filtered post query arguments
		 **/
		private static function filter_taxonomy_post_list_args( $args ) {
			$taxonomies = get_taxonomies();
			$tax_query = array();

			foreach ( $taxonomies as $tax_name ) {
				$inner_tax_query = array();

				if ( isset( $args['tax_' . $tax_name] ) ) {
					$inner_tax_query['taxonomy'] = $tax_name;
					$inner_tax_query['terms'] = $args['tax_' . $tax_name];

					if ( isset( $args['tax_' . $tax_name . '__field'] ) ) {
						$inner_tax_query['field'] = $args['tax_' . $tax_name . '__field'];
					}
					if ( isset( $args['tax_' . $tax_name . '__include_children'] ) ) {
						$inner_tax_query['include_children'] = $args['tax_' . $tax_name . '__include_children'];
					}
					if ( isset( $args['tax_' . $tax_name . '__operator'] ) ) {
						$inner_tax_query['operator'] = $args['tax_' . $tax_name . '__operator'];
					}
				}
				unset(
					$args['tax_' . $tax_name],
					$args['tax_' . $tax_name . '__field'],
					$args['tax_' . $tax_name . '__include_children'],
					$args['tax_' . $tax_name . '__operator']
				);

				if ( !empty( $inner_tax_query ) ) {
					$tax_query[] = $inner_tax_query;
				}
			}

			if ( !empty( $tax_query ) ) {
				$args['tax_query'] = $tax_query;

				// Apply a 'relation' param.  Only set it if multiple tax
				// queries are provided.
				if ( isset( $args['tax_relation'] ) && count( $tax_query ) > 1 ) {
					$args['tax_query']['relation'] = $args['tax_relation'];
					unset( $args['tax_relation'] );
				}
			}

			return $args;
		}

		/**
		 * Provides support for meta queries against serialized meta data for
		 * Advanced Custom Fields relationship fields
		 *
		 * NOTE: this function will need to be updated if meta_query support
		 * is added to the shortcode
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 * @param $args array | array of post query arguments, from post list shortcode
		 * @return array | array of filtered post query arguments
		 **/
		private static function filter_acf_relationship_field_meta( $args ) {
			if ( isset( $args['meta_value'] ) && isset( $args['meta_key'] ) ) {
				$field_obj = acf_get_field( $args['meta_key'] );

				// Is this a field generated by ACF?
				if ( $field_obj && isset( $field_obj['type'] ) ) {

					// Is $field_obj a relationship field?
					if ( $field_obj['type'] == 'relationship' ) {
						// Get all valid post types to query against
						$post_types = isset( $field_obj['post_type'] ) ? $field_obj['post_type'] : 'any';

						// Normalize meta_value into an array of unique post IDs
						$meta_value  = explode( ',', $args['meta_value'] );
						$meta_value_ids   = array_filter( $meta_value, 'is_numeric' ); // get all values that look like post IDs
						$meta_value_slugs = array_diff( $meta_value, $meta_value_ids ); // assume anything else is intended to be a post slug

						if ( !empty( $meta_value_slugs ) ) {
							$posts_by_slug = get_posts( array(
								'post_name__in' => $meta_value_slugs,
								'post_type' => $post_types,
								'fields' => 'ids',
								'numberposts' => -1
							) );
							$meta_value_ids = array_merge( $meta_value_ids, $posts_by_slug );
						}
						$meta_value_ids = array_unique( $meta_value_ids );

						// Pre-fetch posts using string comparisons against
						// serialized metadata for each post ID in meta_value
						$reverse_posts = array();
						if ( $meta_value_ids ) {
							$reverse_post_types = isset( $args['post_type'] ) ? $args['post_type'] : 'any';
							$reverse_query_args = array(
								'post_type'   => $reverse_post_types,
								'numberposts' => -1,
								'meta_query' => array(),
								'fields' => 'ids'
							);
							// Check for our custom 'meta_serialized_relation'
							// arg, which specifies the relation param between
							// meta queries (WP defaults this to "AND"):
							if ( isset( $args['meta_serialized_relation'] ) ) {
								$reverse_query_args['meta_query']['relation'] = $args['meta_serialized_relation'];
							}
							foreach ( $meta_value_ids as $id ) {
								// https://www.advancedcustomfields.com/resources/querying-relationship-fields/
								$reverse_query_args['meta_query'][] = array(
									'key'     => $args['meta_key'], // name of custom field
									'value'   => '"' . $id . '"', // matches exactly "123", not just 123. This prevents a match for "1234"
									'compare' => 'LIKE'
								);
							}
							$reverse_posts = get_posts( $reverse_query_args );
						}

						if ( $reverse_posts ) {
							// Finally, strip out meta_key and meta_value args in favor of
							// using our pre-fetched list of post IDs:
							unset( $args['meta_key'], $args['meta_value'], $args['meta_serialized_relation'] );

							if ( isset( $args['post__in'] ) ) {
								$args['post__in'] = array_intersect( $args['post__in'], $reverse_posts );
							}
							else {
								$args['post__in'] = $reverse_posts;
							}
						}
						else {
							// No related posts match the given meta query--so
							// the rest of the post query should return no
							// results.
							$args = false;
						}
					}

				}
			}

			return $args;
		}
	}
}

if ( ! function_exists( 'ucf_post_list_enqueue_assets' ) ) {
	function ucf_post_list_enqueue_assets() {
		// CSS
		$include_css = UCF_Post_List_Config::get_option_or_default( 'include_css' );
		$css_deps = apply_filters( 'ucf_post_list_style_deps', array() );

		if ( $include_css ) {
			wp_enqueue_style( 'ucf_post_list_css', plugins_url( 'static/css/ucf-post-list.min.css', UCF_POST_LIST__PLUGIN_FILE ), $css_deps, false, 'screen' );
		}

		// JS
		$include_js_libs = UCF_Post_List_Config::get_option_or_default( 'include_js_libs' );
		$include_js = UCF_Post_List_Config::get_option_or_default( 'include_js' );
		$js_deps = apply_filters( 'ucf_post_list_js_deps', $include_js_libs ? array( 'ucf-post-list-typeahead-js', 'ucf-post-list-handlebars-js' ) : array() );

		if ( $include_js_libs ) {
			wp_enqueue_script( 'ucf-post-list-typeahead-js', UCF_POST_LIST__TYPEAHEAD, null, null, false );
			wp_enqueue_script( 'ucf-post-list-handlebars-js', UCF_POST_LIST__HANDLEBARS, null, null, false );
		}
		if ( $include_js ) {
			wp_enqueue_script( 'ucf-post-list-js', plugins_url( 'static/js/ucf-post-list.min.js', UCF_POST_LIST__PLUGIN_FILE ), $js_deps, null, false );
		}
	}

	add_action( 'wp_enqueue_scripts', 'ucf_post_list_enqueue_assets' );
}
