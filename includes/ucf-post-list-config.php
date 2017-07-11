<?php
/**
 * Handles plugin configuration
 */

if ( !class_exists( 'UCF_Post_List_Config' ) ) {

	class UCF_Post_List_Config {
		public static
			$option_prefix = 'ucf_post_list_',
			$option_defaults = array(
				'layout'           => 'default',
				'list_title'       => '',
				'include_css'      => true,

				// Custom argument for ACF relationship fields which defines
				// the relation between reverse lookup posts
				'meta_serialized_relation' => '',

				// get_posts() unique arguments
				// https://codex.wordpress.org/Function_Reference/get_posts

				'numberposts'    => null,  // alias for posts_per_page
				'include'        => array(),  // alias for post__in
				'exclude'        => array(),  // alias for post__not_in

				// https://codex.wordpress.org/Class_Reference/WP_Query

				'author'         => '',  // (int | string) - use author id or comma-separated list of IDs.
				'author_name'    => '',  // (string) - use 'user_nicename' - NOT name.
				'author__in'     => array(),  // (array) - use author id (available since Version 3.7).
				'author__not_in' => array(),  // (array) - use author id (available since Version 3.7).

				'cat'              => '',  // (int) - use category id.
				'category_name'    => '',  // (string) - use category slug.
				'category__and'    => array(), // (array) - use category id.
				'category__in'     => array(), // (array) - use category id.
				'category__not_in' => array(), // (array) - use category id.

				'tag'           => '',  // (string) - use tag slug.
				'tag_id'        => null,  // (int) - use tag id.
				'tag__and'      => array(),  // (array) - use tag ids.
				'tag__in'       => array(),  // (array) - use tag ids.
				'tag__not_in'   => array(),  // (array) - use tag ids.
				'tag_slug__and' => array(),  // (array) - use tag slugs.
				'tag_slug__in'  => array(),  // (array) - use tag slugs.

				// TODO tax_query

				's' => '',  // (string) - Search keyword.

				'p'                   => null,  // (int) - use post id. Default post type is post.
				'name'                => '',  // (string) - use post slug.
				'title'               => '',  // (string) - use post title (available with Version 4.4).
				'page_id'             => null,  // (int) - use page id.
				'pagename'            => '',  // (string) - use page slug.
				'post_parent'         => null,  // (int) - use page id to return only child pages. Set to 0 to return only top-level entries.
				'post_parent__in'     => array(),  // (array) - use post ids. Specify posts whose parent is in an array. (available since Version 3.6)
				'post_parent__not_in' => array(),  // (array) - use post ids. Specify posts whose parent is not in an array. (available since Version 3.6)
				'post__in'            => array(),  // (array) - use post ids. Specify posts to retrieve. ATTENTION If you use sticky posts, they will be included (prepended!) in the posts you retrieve whether you want it or not. To suppress this behaviour use ignore_sticky_posts.
				'post__not_in'        => array(),  // (array) - use post ids. Specify post NOT to retrieve. If this is used in the same query as post__in, it will be ignored.
				'post_name__in'       => array(),  // (array) - use post slugs. Specify posts to retrieve. (available since Version 4.4)

				'has_password'  => null,  // (bool) - true for posts with passwords ; false for posts without passwords ; null for all posts with and without passwords (available since Version 3.9).
				'post_password' => '',  // (string) - show posts with a particular password (available since Version 3.9)

				'post_type' => array(),  // (string / array) - use post types. Retrieves posts by Post Types, default value is 'post'. If 'tax_query' is set for a query, the default value becomes 'any'

				'post_status' => array(),  // (string / array) - use post status. Retrieves posts by Post Status. Default value is 'publish', but if the user is logged in, 'private' is added. Public custom statuses are also included by default. And if the query is run in an admin context (administration area or AJAX call), protected statuses are added too. By default protected statuses are 'future', 'draft' and 'pending'.

				'nopaging'               => null,  // (boolean) - show all posts or use pagination. Default value is 'false', use paging.
				'posts_per_page'         => null,  // (int) - number of post to show per page (available since Version 2.1, replaced showposts parameter). Use 'posts_per_page'=>-1 to show all posts (the 'offset' parameter is ignored with a -1 value). Set the 'paged' parameter if pagination is off after using this parameter. Note: if the query is in a feed, wordpress overwrites this parameter with the stored 'posts_per_rss' option. To reimpose the limit, try using the 'post_limits' filter, or filter 'pre_option_posts_per_rss' and return -1
				'posts_per_archive_page' => null,  // (int) - number of posts to show per page - on archive pages only. Over-rides posts_per_page and showposts on pages where is_archive() or is_search() would be true.
				'offset'                 => null,  // (int) - number of post to displace or pass over. Warning: Setting the offset parameter overrides/ignores the paged parameter and breaks pagination (Click here for a workaround). The 'offset' parameter is ignored when 'posts_per_page'=>-1 (show all posts) is used.
				'paged'                  => null,  // (int) - number of page. Show the posts that would normally show up just on page X when using the "Older Entries" link.
				'page'                   => null,  // (int) - number of page for a static front page. Show the posts that would normally show up just on page X of a Static Front Page.
				'ignore_sticky_posts'    => null,  // (boolean) - ignore post stickiness (available since Version 3.1, replaced caller_get_posts parameter). false (default): move sticky posts to the start of the set. true: do not move sticky posts to the start of the set.

				'order'   => array(),  // (string | array) - Designates the ascending or descending order of the 'orderby' parameter. Defaults to 'DESC'. An array can be used for multiple order/orderby sets.
				'orderby' => array(),  // orderby (string | array) - Sort retrieved posts by parameter. Defaults to 'date (post_date)'. One or more options can be passed.

				'year'     => null,  // (int) - 4 digit year (e.g. 2011).
				'monthnum' => null,  // (int) - Month number (from 1 to 12).
				'w'        => null,  // (int) - Week of the year (from 0 to 53). Uses MySQL WEEK command. The mode is dependent on the "start_of_week" option.
				'day'      => null,  // (int) - Day of the month (from 1 to 31).
				'hour'     => null,  // (int) - Hour (from 0 to 23).
				'minute'   => null,  // (int) - Minute (from 0 to 60).
				'second'   => null,  // (int) - Second (0 to 60).
				'm'        => null,  // (int) - YearMonth (For e.g.: 201307).

				// TODO date_query

				'meta_key'       => '',  // (string) - Custom field key.
				'meta_value'     => '',  // (string) - Custom field value.
				'meta_value_num' => null,  // (number) - Custom field value.
				'meta_compare'   => '',  // (string) - Operator to test the 'meta_value'. Possible values are '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'NOT EXISTS', 'REGEXP', 'NOT REGEXP' or 'RLIKE'. Default value is '='.

				// TODO meta_query

				'perm' => '',  // (string) - User permission.

				'post_mime_type' => array(),  // (string/array) - Allowed mime types (for attachments)

				// NOTE: we don't support caching parameters, 'fields', or 'suppress_filters' in this shortcode
			);

		public static function get_layouts() {
			$layouts = array(
				'default' => 'Default Layout',
			);

			$layouts = apply_filters( self::$option_prefix . 'get_layouts', $layouts );

			return $layouts;
		}

		/**
		 * Creates options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin activation.
		 *
		 * @return void
		 **/
		public static function add_options() {
			$defaults = self::$option_defaults; // don't use self::get_option_defaults() here (default options haven't been set yet)

			add_option( self::$option_prefix . 'include_css', $defaults['include_css'] );
		}

		/**
		 * Deletes options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin uninstallation.
		 *
		 * @return void
		 **/
		public static function delete_options() {
			delete_option( self::$option_prefix . 'include_css' );
		}

		/**
		 * Returns a list of default plugin options. Applies any overridden
		 * default values set within the options page.
		 *
		 * @return array
		 **/
		public static function get_option_defaults() {
			$defaults = self::$option_defaults;

			// Apply default values configurable within the options page:
			$configurable_defaults = array(
				'include_css' => get_option( self::$option_prefix . 'include_css', $defaults['include_css'] )
			);

			// Force configurable options to override $defaults, even if they are empty:
			$defaults = array_merge( $defaults, $configurable_defaults );

			return $defaults;
		}

		/**
		 * Performs typecasting, sanitization, etc on an array of plugin options.
		 *
		 * @param array $list | Assoc. array of plugin options, e.g. [ 'option_name' => 'val', ... ]
		 * @return array
		 **/
		public static function format_options( $list ) {
			foreach ( $list as $key => $val ) {
				switch ( $key ) {
					// Array of integers
					case 'include':
					case 'exclude':
					case 'author__in':
					case 'author__not_in':
					case 'category__and':
					case 'category__in':
					case 'category__not_in':
					case 'tag__and':
					case 'tag__in':
					case 'tag__not_in':
					case 'post_parent__in':
					case 'post_parent__not_in':
					case 'post__in':
					case 'post__not_in':
						$list[$key] = !is_array( $val ) ? array_map( 'intval', array_filter( explode( ',', $val ), 'is_numeric' ) ) : $val;
						break;

					// Array of strings
					case 'tag_slug__and':
					case 'tag_slug__in':
					case 'post_name__in':
						$list[$key] = !is_array( $val ) ? array_map( 'sanitize_text_field', explode( ',', $val ) ) : $val;
						break;

					// Array of strings or string
					case 'post_type':
					case 'post_status':
					case 'order':
					case 'post_mime_type':
						if ( !is_array( $val ) ) {
							if ( strpos( $val, ',' ) !== false ) {
								$list[$key] = array_map( 'sanitize_text_field', explode( ',', $val ) );
							}
							else {
								$list[$key] = sanitize_text_field( $val );
							}
						}
						else {
							$list[$key] = array_map( 'sanitize_text_field', $val );
						}
						break;

					// Custom associative array syntax (param="key1=>val1,key2=>val2") or a single string value
					case 'orderby':
						if ( !is_array( $val ) ) {
							if ( strpos( $val, ',' ) !== false ) {
								$list[$key] = array();
								$val_split = explode( ',', $val );

								foreach ( $val_split as $keyval_pair ) {
									$keyval_split = explode( '=&gt;', $keyval_pair );
									if ( isset( $keyval_split[0] ) && isset( $keyval_split[1] ) ) {
										$list[$key][sanitize_key( trim( $keyval_split[0] ) )] = sanitize_text_field( trim( $keyval_split[1] ) );
									}
								}
							}
							else {
								$list[$key] = sanitize_text_field( $val );
							}
						}
						else {
							$list[$key] = array_map( 'sanitize_text_field', $val );
						}
						break;

					// Integer (can be null)
					case 'numberposts':
					case 'tag_id':
					case 'p':
					case 'page_id':
					case 'post_parent':
					case 'posts_per_page':
					case 'posts_per_archive_page':
					case 'offset':
					case 'paged':
					case 'page':
					case 'year':
					case 'monthnum':
					case 'w':
					case 'day':
					case 'hour':
					case 'minute':
					case 'second':
					case 'm':
						$list[$key] = is_null( $val ) ? $val : intval( $val );
						break;

					// Number (can be null)
					case 'meta_value_num':
						$list[$key] = is_null( $val ) ? $val : $val + 0;
						break;

					// Boolean (can be null)
					case 'has_password':
					case 'nopaging':
					case 'ignore_sticky_posts':
						$list[$key] = is_null( $val ) ? $val : filter_var( $val, FILTER_VALIDATE_BOOLEAN );
						break;

					// Boolean (never null)
					case 'include_css':
						$list[$key] = filter_var( $val, FILTER_VALIDATE_BOOLEAN );
						break;

					// String
					default:
						$list[$key] = sanitize_text_field( $val );
						break;
				}
			}

			return $list;
		}

		/**
		 * Applies formatting to a single option. Intended to be passed to the
		 * 'option_{$option}' hook.
		 **/
		public static function format_option( $value, $option_name ) {
			$option_formatted = self::format_options( array( $option_name => $value ) );
			return $option_formatted[$option_name];
		}

		/**
		 * Applies formatting to an array of shortcode attributes. Intended to
		 * be passed to the 'shortcode_atts_sc_ucf_post_list' hook.
		 **/
		public static function format_sc_atts( $out, $pairs, $atts, $shortcode ) {
			return self::format_options( $out );
		}

		/**
		 * Adds filters for shortcode and plugin options that apply our
		 * formatting rules to attribute/option values.
		 **/
		public static function add_option_formatting_filters() {
			// Options
			$defaults = self::$option_defaults;
			foreach ( $defaults as $option => $default ) {
				add_filter( 'option_{$option}', array( 'UCF_Post_List_Config', 'format_option' ), 10, 2 );
			}
			// Shortcode atts
			add_filter( 'shortcode_atts_ucf-post-list', array( 'UCF_Post_List_Config', 'format_sc_atts' ), 10, 4 );
		}

		/**
		 * Convenience method for returning an option from the WP Options API
		 * or a plugin option default.
		 *
		 * @param $option_name
		 * @return mixed
		 **/
		public static function get_option_or_default( $option_name ) {
			// Handle $option_name passed in with or without self::$option_prefix applied:
			$option_name_no_prefix = str_replace( self::$option_prefix, '', $option_name );
			$option_name           = self::$option_prefix . $option_name_no_prefix;
			$defaults              = self::get_option_defaults();

			return get_option( $option_name, $defaults[$option_name_no_prefix] );
		}

		/**
		 * Initializes setting registration with the Settings API.
		 **/
		public static function settings_init() {
			// Register settings
			register_setting( 'ucf_post_list', self::$option_prefix . 'include_css' );

			// Register setting sections
			add_settings_section(
				'ucf_post_list_section_general', // option section slug
				'General Settings', // formatted title
				'', // callback that echoes any content at the top of the section
				'ucf_post_list' // settings page slug
			);

			add_settings_field(
				self::$option_prefix . 'include_css',
				'Include Default CSS',  // formatted field title
				array( 'UCF_Post_List_Config', 'display_settings_field' ),  // display callback
				'ucf_post_list',  // settings page slug
				'ucf_post_list_section_general',  // option section slug
				array(  // extra arguments to pass to the callback function
					'label_for'   => self::$option_prefix . 'include_css',
					'description' => 'Include the default css stylesheet for post lists within the theme.<br>Leave this checkbox checked unless your theme provides custom styles for post lists.',
					'type'        => 'checkbox'
				)
			);
		}

		/**
		 * Displays an individual setting's field markup.
		 **/
		public static function display_settings_field( $args ) {
			$option_name   = $args['label_for'];
			$description   = $args['description'];
			$field_type    = $args['type'];
			$current_value = self::get_option_or_default( $option_name );
			$markup        = '';

			switch ( $field_type ) {
				case 'checkbox':
					ob_start();
				?>
					<input type="checkbox" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" <?php echo ( $current_value == true ) ? 'checked' : ''; ?>>
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;

				case 'number':
					ob_start();
				?>
					<input type="number" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;

				case 'text':
				default:
					ob_start();
				?>
					<input type="text" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;
			}
		?>

		<?php
			echo $markup;
		}


		/**
		 * Registers the settings page to display in the WordPress admin.
		 **/
		public static function add_options_page() {
			$page_title = 'UCF Post List Settings';
			$menu_title = 'UCF Post List';
			$capability = 'manage_options';
			$menu_slug  = 'ucf_post_list';
			$callback   = array( 'UCF_Post_List_Config', 'options_page_html' );

			return add_options_page(
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback
			);
		}


		/**
		 * Displays the plugin's settings page form.
		 **/
		public static function options_page_html() {
			ob_start();
		?>

		<div class="wrap">
			<h1><?php echo get_admin_page_title(); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'ucf_post_list' );
				do_settings_sections( 'ucf_post_list' );
				submit_button();
				?>
			</form>
		</div>

		<?php
			echo ob_get_clean();
		}

	}

	// Register settings and options.
	add_action( 'admin_init', array( 'UCF_Post_List_Config', 'settings_init' ) );
	add_action( 'admin_menu', array( 'UCF_Post_List_Config', 'add_options_page' ) );

	// Apply custom formatting to shortcode attributes and options.
	UCF_Post_List_Config::add_option_formatting_filters();
}
