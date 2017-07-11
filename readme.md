# UCF Post List Shortcode #

Provides a shortcode for displaying lists of posts.


## Description ##

This plugin provides a shortcode for displaying lists of posts.  It is written to work out-of-the-box for non-programmers, but is also extensible and customizable for developers.

Support for Advanced Custom Fields relationship fields is also available; e.g. you can query against a list of posts by one or more related posts: `[ucf-post-list meta_key="your_relationship_field" meta_value="related-post-slug-1,related-post-slug-2"]`

The `[ucf-post-list]` shortcode accepts most arguments for `get_post()` and `WP Query`.  See the documentation for [`get_post`](https://codex.wordpress.org/Function_Reference/get_posts) and [`WP Query`](https://codex.wordpress.org/Class_Reference/WP_Query) for more information.

Note that caching parameters, `fields`, and `suppress_filters` arguments are not supported by the `[ucf-post-list]` shortcode.  In addition, `tax_query`, `date_query` and `meta_query` are not yet supported.


## Installation ##

### Manual Installation ###
1. Upload the plugin files (unzipped) to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress
3. Configure plugin settings from the WordPress admin under "Settings > UCF Post List".

### WP CLI Installation ###
1. `$ wp plugin install --activate https://github.com/UCF/UCF-Post-List-Shortcode/archive/master.zip`.  See [WP-CLI Docs](http://wp-cli.org/commands/plugin/install/) for more command options.
2. Configure plugin settings from the WordPress admin under "Settings > UCF Post List".


## Changelog ##

### 0.0.0 ###
* Initial release


## Upgrade Notice ##

n/a


## Installation Requirements ##

None


## Development & Contributing ##

NOTE: this plugin's readme.md file is automatically generated.  Please only make modifications to the readme.txt file, and make sure the `gulp readme` command has been run before committing readme changes.
