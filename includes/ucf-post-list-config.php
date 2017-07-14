<?php
/**
 * Handles plugin configuration
 */

if ( !class_exists( 'UCF_Post_List_Option' ) ) {

	class UCF_Post_List_Option {
		public
			$option_name,
			$default = null,  // default value for the option
			$format_callback = 'sanitize_text_field',  // function that formats the option value
			$options_page = false,  // whether the option should be configurable via the plugin options page
			$sc_attr = true;  // whether the option should be a valid shortcode attribute

		function __construct( $option_name, $args=array() ) {
			$this->option_name     = $option_name;
			$this->default         = isset( $args['default'] ) ? $args['default'] : $this->default;
			$this->format_callback = isset( $args['format_callback'] ) ? $args['format_callback'] : $this->format_callback;
			$this->options_page    = isset( $args['options_page'] ) ? $args['options_page'] : $this->options_page;
			$this->sc_attr         = isset( $args['sc_attr'] ) ? $args['sc_attr'] : $this->sc_attr;
		}
	}

}

if ( !class_exists( 'UCF_Post_List_Config' ) ) {

	class UCF_Post_List_Config {
		public static
			$option_prefix = 'ucf_post_list_';
		private static
			$options = array(
				'layout'      => array(
					'default' => 'default'
				),
				'list_title'  => array(),
				'include_css' => array(
					'default'         => true,
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_boolean' ), // TODO
					'options_page'    => true,
					'sc_attr'         => false // TODO apply this somehwere
				),

				// Custom argument for ACF relationship fields which defines
				// the relation between reverse lookup posts
				'meta_serialized_relation' => array(),

				// get_posts() unique arguments
				// https://codex.wordpress.org/Function_Reference/get_posts

				'category'    => array(),  // alias for cat
				'numberposts' => array(  // alias for posts_per_page
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' ), // TODO
				),
				'include'     => array(  // alias for post__in
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' ) // TODO
				),
				'exclude'     => array(  // alias for post__not_in
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),

				// https://codex.wordpress.org/Class_Reference/WP_Query

				// Author
				'author'         => array(),
				'author_name'    => array(),
				'author__in'     => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'author__not_in' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),

				// Category
				'cat'              => array(),
				'category_name'    => array(),
				'category__and'    => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'category__in'     => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'category__not_in' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),

				// Tag
				'tag'         => array(),
				'tag_id'      => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' ) // TODO
				),
				'tag__and'    => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'tag__in'     => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'tag__not_in' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'tag_slug__and' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_array' ) // TODO
				),
				'tag_slug__in'  => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_array' )
				),

				// TODO tax_query

				// Search
				's' => array(),

				// Post
				'p'        => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				),
				'name'     => array(),
				'title'    => array(),
				'page_id'  => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				),
				'pagename' => array(),
				'post_parent'         => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				),
				'post_parent__in'     => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'post_parent__not_in' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'post__in'      => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'post__not_in'  => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				),
				'post_name__in' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_array' )
				),

				// Password
				'has_password'  => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				),
				'post_password' => array(),

				// Post Type
				'post_type' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_or_array' )
				),

				// Post Status
				'post_status' => array(),

				// Pagination/Offset
				'nopaging' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				),
				'posts_per_page' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'posts_per_archive_page' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'offset' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'paged'  => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'page'   => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'ignore_sticky_posts' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				),

				// Order
				'order'   => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_or_array' )
				),
				'orderby' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_array_str' )
				),

				// Date
				'year'     => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'monthnum' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'w'        => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'day'      => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'hour'     => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'minute'   => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'second'   => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'm'        => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),

				// TODO date_query

				// Meta fields
				'meta_key'       => array(),
				'meta_value'     => array(),
				'meta_value_num' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				),
				'meta_compare'   => array(),

				// TODO meta_query

				// Permissions
				'perm' => array(),

				// Mimetypes
				'post_mime_type' => array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_or_array' )
				),

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
		 * Returns whether or not an option is configurable on the plugin
		 * options page.
		 *
		 * @return boolean
		 **/
		public static function option_is_configurable( $option_obj ) {
			return $option_obj->options_page;
		}

		/**
		 * Creates options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin activation.
		 *
		 * @return void
		 **/
		public static function add_options() {
			$options = array_filter( self::options(), array( 'UCF_Post_List_Config', 'option_is_configurable' ) );
			if ( $options ) {
				foreach ( $options as $option ) {
					add_option( self::$option_prefix . $option->option_name, $option->default );
				}
			}
		}

		/**
		 * Deletes options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin uninstallation.
		 *
		 * @return void
		 **/
		public static function delete_options() {
			$options = array_filter( self::options(), array( 'UCF_Post_List_Config', 'filter_options_configurable' ) );
			if ( $options ) {
				foreach ( $options as $option ) {
					delete_option( self::$option_prefix . $option->option_name );
				}
			}
		}

		public static function normalize_option( $option, $option_name ) {
			return new UCF_Post_List_Option( $option_name, $option );
		}

		/**
		 * Returns a list of default plugin options.  Applies additional option
		 * defaults on-the-fly based on registered post types and taxonomies.
		 * Does not apply option value overrides set by the user in plugin
		 * settings (see self::get_option_defaults() instead.)
		 *
		 * Use this method to return option defaults instead of accessing
		 * self::$option_defaults directly.
		 *
		 * @return array
		 **/
		public static function options() {
			$optoins = self::$options;

			// Add defaults for per-taxonomy queries.  While it's impractical
			// to fully support nested tax_query statements, we can at least
			// support single-level queries against registered taxonomies.
			$taxonomies = get_taxonomies();
			foreach ( $taxonomies as $tax_name ) {
				if ( !isset( $defaults['tax_' . $tax_name] ) ) {
					$options['tax_' . $tax_name] = array();
					$options['tax_' . $tax_name . '__field'] = array();
					$options['tax_' . $tax_name . '__terms'] = array(
						'format_callback' => array( 'UCF_Post_List_Config', 'format_option_array_str' )
					);
					$options['tax_' . $tax_name . '__include_children'] = array(
						'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
					);
					$options['tax_' . $tax_name . '__operator'] = array();
				}
			}

			return array_walk( $options, array( 'UCF_Post_List_Config', 'normalize_option' ) );
		}

		/**
		 * Returns a list of default plugin option values. Applies any
		 * overridden default values set within the options page.
		 *
		 * @return array
		 **/
		public static function get_option_defaults() {
			$options = self::options();
			$configurable_options = array_filter( $options, array( 'UCF_Post_List_Config', 'filter_options_configurable' ) );
			$defaults = array();

			// Filter out just the option names/default values:
			foreach ( $options as $option_name => $option ) {
				$defaults[$option_name] = $option->default;
			}

			// Apply default values configurable within the options page:
			if ( $configurable_options ) {
				foreach ( $configurable_options as $option_name => $option ) {
					$defaults[$option_name] => get_option( self::$option_prefix . $option_name, $option->default );
				}
			}

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
			$defaults = self::options();
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
