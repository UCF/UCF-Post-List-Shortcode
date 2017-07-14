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

		/**
		 * Returns the default value for the option, with the Options API value
		 * applied if $apply_configurable_val and $this->options_page are true.
		 **/
		function get_default( $apply_configurable_val=true ) {
			$default = $this->default;
			if ( $this->options_page && $apply_configurable_val ) {
				$default = get_option( UCF_Post_List_Config::$option_prefix . $this->option_name, $default );
			}
			return $default;
		}

		/**
		 * Returns the formatted value, using the function name passed to
		 * $this->format_callback.
		 **/
		function format( $value ) {
			return call_user_func( $this->format_callback, $value );
		}
	}

}

if ( !class_exists( 'UCF_Post_List_Config' ) ) {

	class UCF_Post_List_Config {
		public static
			$option_prefix = 'ucf_post_list_';


		public static function get_layouts() {
			$layouts = array(
				'default' => 'Default Layout',
			);

			$layouts = apply_filters( self::$option_prefix . 'get_layouts', $layouts );

			return $layouts;
		}

		/**
		 * Returns a full list of plugin option objects.  Adds additional
		 * options on-the-fly based on registered post types and taxonomies.
		 *
		 * @return array
		 **/
		public static function get_options() {
			$options = array(
				'layout'      => new UCF_Post_List_Option( 'layout', array(
					'default' => 'default'
				) ),
				'list_title'  => new UCF_Post_List_Option( 'list_title' ),
				'include_css' => new UCF_Post_List_Option( 'include_css', array(
					'default'         => true,
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool' ),
					'options_page'    => true,
					'sc_attr'         => false
				) ),

				// Custom argument that defines the top-level relationship
				// between tax_query arguments
				'tax_relation' => new UCF_Post_List_Option( 'tax_relation' ),

				// Custom argument for ACF relationship fields which defines
				// the relation between reverse lookup posts
				'meta_serialized_relation' => new UCF_Post_List_Option( 'meta_serialized_relation' ),

				// get_posts() unique arguments
				// https://codex.wordpress.org/Function_Reference/get_posts

				'category'    => new UCF_Post_List_Option( 'category' ),  // alias for cat
				'numberposts' => new UCF_Post_List_Option( 'numberposts', array(  // alias for posts_per_page
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' ),
				) ),
				'include'     => new UCF_Post_List_Option( 'include', array(  // alias for post__in
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'exclude'     => new UCF_Post_List_Option( 'exclude', array(  // alias for post__not_in
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),

				// https://codex.wordpress.org/Class_Reference/WP_Query

				// Author
				'author'         => new UCF_Post_List_Option( 'author' ),
				'author_name'    => new UCF_Post_List_Option( 'author_name' ),
				'author__in'     => new UCF_Post_List_Option( 'author__in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'author__not_in' => new UCF_Post_List_Option( 'author__not_in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),

				// Category
				'cat'              => new UCF_Post_List_Option( 'cat' ),
				'category_name'    => new UCF_Post_List_Option( 'category_name' ),
				'category__and'    => new UCF_Post_List_Option( 'category__and', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'category__in'     => new UCF_Post_List_Option( 'category__in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'category__not_in' => new UCF_Post_List_Option( 'category__not_in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),

				// Tag
				'tag'         => new UCF_Post_List_Option( 'tag' ),
				'tag_id'      => new UCF_Post_List_Option( 'tag_id', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'tag__and'    => new UCF_Post_List_Option( 'tag__and', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'tag__in'     => new UCF_Post_List_Option( 'tag__in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'tag__not_in' => new UCF_Post_List_Option( 'tag__not_in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'tag_slug__and' => new UCF_Post_List_Option( 'tag_slug__and', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_array' )
				) ),
				'tag_slug__in'  => new UCF_Post_List_Option( 'tag_slug__in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_array' )
				) ),

				// NOTE: tax_query support is added dynamically based on custom
				// per-taxonomy attributes; see
				// UCF_Post_List_Common::filter_taxonomy_post_list_args()

				// Search
				's' => new UCF_Post_List_Option( 's' ),

				// Post
				'p'        => new UCF_Post_List_Option( 'p', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				) ),
				'name'     => new UCF_Post_List_Option( 'name' ),
				'title'    => new UCF_Post_List_Option( 'title' ),
				'page_id'  => new UCF_Post_List_Option( 'page_id', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				) ),
				'pagename' => new UCF_Post_List_Option( 'pagename' ),
				'post_parent'         => new UCF_Post_List_Option( 'post_parent', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				) ),
				'post_parent__in'     => new UCF_Post_List_Option( 'post_parent__in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'post_parent__not_in' => new UCF_Post_List_Option( 'post_parent__not_in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'post__in'      => new UCF_Post_List_Option( 'post__in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'post__not_in'  => new UCF_Post_List_Option( 'post__not_in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_array' )
				) ),
				'post_name__in' => new UCF_Post_List_Option( 'post_name__in', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_array' )
				) ),

				// Password
				'has_password'  => new UCF_Post_List_Option( 'has_password', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				) ),
				'post_password' => new UCF_Post_List_Option( 'post_password' ),

				// Post Type
				'post_type' => new UCF_Post_List_Option( 'post_type', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_or_array' )
				) ),

				// Post Status
				'post_status' => new UCF_Post_List_Option( 'post_status' ),

				// Pagination/Offset
				'nopaging' => new UCF_Post_List_Option( 'nopaging', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				) ),
				'posts_per_page' => new UCF_Post_List_Option( 'posts_per_page', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'posts_per_archive_page' => new UCF_Post_List_Option( 'posts_per_archive_page', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'offset' => new UCF_Post_List_Option( 'offset', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'paged'  => new UCF_Post_List_Option( 'paged', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'page'   => new UCF_Post_List_Option( 'page', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'ignore_sticky_posts' => new UCF_Post_List_Option( 'ignore_sticky_posts', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
				) ),

				// Order
				'order'   => new UCF_Post_List_Option( 'order', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_or_array' )
				) ),
				'orderby' => new UCF_Post_List_Option( 'orderby', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_array_str' )
				) ),

				// Date
				'year'     => new UCF_Post_List_Option( 'year', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'monthnum' => new UCF_Post_List_Option( 'monthnum', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'w'        => new UCF_Post_List_Option( 'w', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'day'      => new UCF_Post_List_Option( 'day', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'hour'     => new UCF_Post_List_Option( 'hour', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'minute'   => new UCF_Post_List_Option( 'minute', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'second'   => new UCF_Post_List_Option( 'second', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),
				'm'        => new UCF_Post_List_Option( 'm', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_int_or_null' )
				) ),

				// TODO date_query

				// Meta fields
				'meta_key'       => new UCF_Post_List_Option( 'meta_key' ),
				'meta_value'     => new UCF_Post_List_Option( 'meta_value' ),
				'meta_value_num' => new UCF_Post_List_Option( 'meta_value_num', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_num_or_null' )
				) ),
				'meta_compare'   => new UCF_Post_List_Option( 'meta_compare' ),

				// TODO meta_query

				// Permissions
				'perm' => new UCF_Post_List_Option( 'perm' ),

				// Mimetypes
				'post_mime_type' => new UCF_Post_List_Option( 'post_mime_type', array(
					'format_callback' => array( 'UCF_Post_List_Config', 'format_option_str_or_array' )
				) ),

				// NOTE: we don't support caching parameters, 'fields', or 'suppress_filters' in this shortcode
			);

			// Add defaults for per-taxonomy queries.  While it's impractical
			// to fully support nested tax_query statements, we can at least
			// support single-level queries against registered taxonomies.
			$taxonomies = get_taxonomies();
			foreach ( $taxonomies as $tax_name ) {
				if ( !isset( $options['tax_' . $tax_name] ) ) {
					$options['tax_' . $tax_name] = new UCF_Post_List_Option( 'tax_' . $tax_name, array(
						'format_callback' => array( 'UCF_Post_List_Config', 'format_option_array_str' )
					) );
					$options['tax_' . $tax_name . '__field'] = new UCF_Post_List_Option( 'tax_' . $tax_name. '__field' );
					$options['tax_' . $tax_name . '__include_children'] = new UCF_Post_List_Option( 'tax_' . $tax_name . '__include_children', array(
						'format_callback' => array( 'UCF_Post_List_Config', 'format_option_bool_or_null' )
					) );
					$options['tax_' . $tax_name . '__operator'] = new UCF_Post_List_Option( 'tax_' . $tax_name . '__operator' );
				}
			}

			return $options;
		}

		/**
		 * Returns an option object or false if it doesn't exist.
		 *
		 * @return UCF_Post_List_Option object
		 **/
		public static function get_option( $option_name ) {
			$options = self::get_options();
			return isset( $options[$option_name] ) ? $options[$option_name] : false;
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
		 * Returns whether or not an option is a valid shortcode attribute.
		 *
		 * @return boolean
		 **/
		public static function option_is_sc_attr( $option_obj ) {
			return $option_obj->sc_attr;
		}

		/**
		 * Creates options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin activation.
		 *
		 * @return void
		 **/
		public static function add_configurable_options() {
			$options = array_filter( self::get_options(), array( 'UCF_Post_List_Config', 'option_is_configurable' ) );
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
		public static function delete_configurable_options() {
			$options = array_filter( self::get_options(), array( 'UCF_Post_List_Config', 'option_is_configurable' ) );
			if ( $options ) {
				foreach ( $options as $option ) {
					delete_option( self::$option_prefix . $option->option_name );
				}
			}
		}

		/**
		 * Returns an array of option name+default key+value pairs for all
		 * valid shortcode attributes.
		 **/
		public static function get_shortcode_atts() {
			$options = array_filter( self::get_options(), array( 'UCF_Post_List_Config', 'option_is_sc_attr' ) );
			$sc_atts = array();
			if ( $options ) {
				foreach ( $options as $option_name => $option ) {
					$sc_atts[$option_name] = $option->get_default();
				}
			}
			return $sc_atts;
		}

		/**
		 * Formats $val as a boolean value.
		 **/
		public static function format_option_bool( $val ) {
			return filter_var( $val, FILTER_VALIDATE_BOOLEAN );
		}

		/**
		 * Formats $val as a boolean value.  Allows null values.
		 **/
		public static function format_option_bool_or_null( $val ) {
			return is_null( $val ) ? $val : filter_var( $val, FILTER_VALIDATE_BOOLEAN );
		}


		/**
		 * Formats $val as an integer.  Allows null values.
		 **/
		public static function format_option_int_or_null( $val ) {
			return is_null( $val ) ? $val : intval( $val );
		}

		/**
		 * Formats $val as an array of integers.
		 **/
		public static function format_option_int_array( $val ) {
			if ( is_string( $val ) ) {
				return array_map( 'intval', array_filter( explode( ',', $val ), 'is_numeric' ) );
			}
			else if ( is_array( $val ) ) {
				return array_map( 'intval', $val );
			}
			else {
				return array();
			}
		}

		/**
		 * Formats $val as an array of strings.
		 **/
		public static function format_option_str_array( $val ) {
			if ( is_string( $val ) ) {
				return array_map( 'sanitize_text_field', explode( ',', $val ) );
			}
			else if ( is_array( $val ) ) {
				return array_map( 'sanitize_text_field', $val );
			}
			else {
				return array();
			}
		}

		/**
		 * Formats $val as a string or array of strings.
		 **/
		public static function format_option_str_or_array( $val ) {
			if ( !is_array( $val ) ) {
				if ( strpos( $val, ',' ) !== false ) {
					return array_map( 'sanitize_text_field', explode( ',', $val ) );
				}
				else {
					return sanitize_text_field( $val );
				}
			}
			else {
				return array_map( 'sanitize_text_field', $val );
			}
		}

		/**
		 * Formats $val as an array using a custom associative array syntax
		 * (param="key1=>val1,key2=>val2"), or as a single string value
		 **/
		public static function format_option_array_str( $val ) {
			$formatted_val = '';
			if ( !is_array( $val ) ) {
				if ( strpos( $val, ',' ) !== false ) {
					$formatted_val = array();
					$val_split = explode( ',', $val );

					foreach ( $val_split as $keyval_pair ) {
						$keyval_split = explode( '=&gt;', $keyval_pair );
						if ( isset( $keyval_split[0] ) && isset( $keyval_split[1] ) ) {
							$formatted_val[sanitize_key( trim( $keyval_split[0] ) )] = sanitize_text_field( trim( $keyval_split[1] ) );
						}
					}
				}
				else {
					$formatted_val = sanitize_text_field( $val );
				}
			}
			else {
				$formatted_val = array_map( 'sanitize_text_field', $val );
			}
			return $formatted_val;
		}

		/**
		 * Formats $val as a number.  Allows null values.
		 **/
		public static function format_option_num_or_null( $val ) {
			return is_null( $val ) ? $val : $val + 0;
		}

		/**
		 * Applies formatting to a single configurable option. Intended to be
		 * passed to the 'option_{$option}' hook.
		 **/
		public static function format_configurable_option( $value, $option_name ) {
			$option = self::get_option( $option_name );
			if ( $option ) {
				return $option->format( $value );
			}
			return false;
		}

		/**
		 * Applies formatting to an array of shortcode attributes. Intended to
		 * be passed to the 'shortcode_atts_ucf-post-list' hook.
		 **/
		public static function format_sc_atts( $out, $pairs, $atts, $shortcode ) {
			foreach ( $out as $key=>$val ) {
				if ( $option = self::get_option( $key ) ) {
					$out[$key] = $option->format( $val );
				}
			}
			return $out;
		}

		/**
		 * Adds filters for shortcode and plugin options that apply our
		 * formatting rules to attribute/option values.
		 **/
		public static function add_option_formatting_filters() {
			// Options
			$options = self::get_options();
			foreach ( $options as $option_name => $option ) {
				add_filter( 'option_{$option_name}', array( 'UCF_Post_List_Config', 'format_configurable_option' ), 10, 2 );
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
			$option                = self::get_option( $option_name_no_prefix );

			if ( $option ) {
				return get_option( $option_name, $option->get_default() );
			}
			else {
				return get_option( $option_name );
			}
		}

		/**
		 * Initializes setting registration with the Settings API.
		 * TODO move field configuration to option obj
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
		 * TODO move to option obj?
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
