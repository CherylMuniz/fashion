<?php
/*
 * Plugin Name: OpenHook
 * Plugin URI: http://www.openhook.net/
 * Description: Achieving popularity by empowering the <a href="http://get-thesis.com/">Thesis theme</a> community, OpenHook opens the door to future-proof customization of WordPress, Thesis, and now Headway by storing your customizations safely in your database. In addition to supporting Headway, OpenHook 4.0 now provides a handful of useful shortcodes for even more customization possibility!
 * Version: 4.0.1
 * Author: Rick Beckman
 * Author URI: http://rickbeckman.org/
 * License: GNU General Public License v3.0 (or later)
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: openhook
 * Domain Path: /languages/
*/

/**
 * Prevent direct access to this file
 */
if ( ! defined( 'DB_NAME' ) ) {
	@header( 'HTTP/1.1 403 Forbidden' );
	@header( 'Status: 403 Forbidden' );
	@header( 'Connection: Close' );
	@exit;
}

/**
 * Define OpenHook constants
 */
define( 'OPENHOOK_VERSION', '7' ); # Dev version (used for internal feature tracking)
define( 'OPENHOOK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'OPENHOOK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

class OpenHook {
	/**
	 * Initializes the OpenHook class
	 *
	 * Runs through several actions, such as activating our i18n text domain, required to get us started
	 *
	 * @since 3.4
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		add_action( 'init', array( $this, 'upgrade' ) ); # Upgrade, if needed
		add_action( 'init', array( $this, 'i18n' ) ); # Internationalization
		add_action( 'init', array( $this, 'execute_hooks' ) ); # Attach custom actions to hooks
		add_action( 'init', array( $this, 'register_shortcodes' ) ); # Turn on chosen shortcodes

		# The meat & potatoes of OpenHook processes on the frontend only
		if ( ! is_admin() ) {
			# Handle hook visualization
			$options = get_option( 'openhook_general' );
			if ( isset( $options[ 'visualize_hooks' ] ) && $options[ 'visualize_hooks' ] )
				add_action( 'init', array( $this, 'hook_visualization_setup' ) );
		}
		# This stuff process admin-side only
		else {
			include_once( OPENHOOK_PLUGIN_DIR . 'admin.php' );

			$openhook_admin = new OpenHook_Admin();

			add_action( 'admin_init', array( $openhook_admin, 'initiate_options' ) );
			add_action( 'admin_menu', array( $openhook_admin, 'add_admin_menu_links' ), 99 );

		}
	}

	/**
	 * We don't need to do much on initial install... but what little we need, we has
	 *
	 * @since 3.4
	 */
	public function activate( $network_wide ) {
		# Install OpenBox
		$this->install_openbox();

		# Set version in database
		update_option( 'openhook_version', OPENHOOK_VERSION );
	}

	/**
	 * Handle anything that needs upgraded aside from standard plugin files
	 *
	 * @since 3.4
	 */
	public function upgrade() {
		# Ensure OpenBox stays upgraded
		if ( version_compare( get_option( 'openhook_version' ), OPENHOOK_VERSION ) )
			$this->install_openbox();
	}

	/**
	 * Loads the plugin text domain for translation
	 *
	 * @since 3.4
	 */
	public function i18n() {
		load_plugin_textdomain( 'openhook', false, plugin_dir_path( __FILE__ ) . 'languages' );
	}

	/**
	 * Get the authorization level required to use OpenHook's features
	 *
	 * @since 4.0
	 */
	public function get_auth_level() {
		return 'delete_users';
	}

	/**
	 * Registers OpenHook's various shortcode customizations
	 *
	 * @since 4.0
	 */
	public function register_shortcodes() {
		$options = get_option( 'openhook_shortcodes' );

		# If shortcodes are disable, nuke them all using WordPress' function
		if ( isset( $options[ 'disable_all' ] ) && $options[ 'disable_all' ] ) {
			remove_all_shortcodes();
		}
		else {
			# Include our shortcode functions
			include_once( OPENHOOK_PLUGIN_DIR . 'shortcodes.php' );

			# Set up our shortcodes object
			$shortcodes = new OpenHook_ShortCodes();

			# Register each of our shortcodes, if applicable
			if ( isset( $options[ 'email' ] ) && $options [ 'email' ] )
				add_shortcode( 'email', array( $shortcodes, 'email' ) );
			if ( isset( $options[ 'global_enabled' ] ) && $options[ 'global_enabled' ] )
				add_shortcode( 'global', array( $shortcodes, 'globals' ) );
			if ( isset( $options[ 'php' ] ) && $options[ 'php' ] )
				add_shortcode( 'php', array( $shortcodes, 'php' ) );
		}
	}

	/**
	 * Setup hook visualizations
	 *
	 * @since 3.3
	 */
	public function hook_visualization_setup() {
		$auth = $this->get_auth_level();

		# Prevent hook visualizations from appearing to users which can't edit the hooks to begin with
		if ( current_user_can( $auth ) ) {
			# Get a list of all hooks about which OpenHook knows
			$headway_hooks = $this->headway_hooks();
			$thesis_hooks = $this->thesis_hooks();
			$wordpress_hooks = $this->wordpress_hooks();
			$all_hooks = array_merge( (array) $headway_hooks, (array) $thesis_hooks, (array) $wordpress_hooks );

			# Register the style needed to highlight hook locations
			wp_enqueue_style( 'openhook-visualization', OPENHOOK_PLUGIN_URL . 'style-visualization.css' );

			# Cycle through each of the hooks, adding the visualization code to it
			foreach ( $all_hooks as $hook ) {
				add_action( $hook[ 'name' ], array( $this, 'do_hook_visualization' ), 1 );
			}
		}
	}

	/**
	 * Output hook name/visualization
	 *
	 * @since 3.3
	 */
	public function do_hook_visualization() {
		# Get the current hook to be highlighted
		$current_action = current_filter();

		# Highlight each hook, except for "wp_head", which must be "highlighted" invisibly
		if ( $current_action != 'wp_head' )
			echo '<span class="openhook">' . $current_action . '</span>';
		else
			echo '<!-- wp_head -->';
	}

	/**
	 * Determine which actions to take for each of our hooks
	 *
	 * @since 3.0
	 */
	public function execute_hooks() {
		$active_hooks = $this->get_active_hooks();
		$all_actions = $this->get_active_actions();

		# Go through each of our hooks, doing stuff if needed
		foreach ( $active_hooks as $hook ) {
			# Add actions to all required hooks
			if ( isset( $all_actions[ $hook[ 'name' ] ][ 'action' ] ) && ! isset ( $all_actions[ $hook[ 'name' ] ][ 'disable' ] ) )
				add_action( $hook[ 'name' ], array( $this, 'execute_action' ) );

			# Unhook actions as needed
			if ( isset( $hook[ 'unhook' ] ) ) {
				foreach ( $hook[ 'unhook' ] as $action ) {
					if ( isset( $all_actions[ $hook[ 'name' ] ][ 'unhook' ][ $action[ 'name' ] ] ) ) {
						$priority = isset( $action[ 'priority' ] ) ? $action[ 'priority' ] : false;

						# Actions hooked with a priority need a priority to be unhooked
						if ( $priority )
							remove_action( $hook[ 'name' ], $action[ 'name' ], $priority );
						else
							remove_action( $hook[ 'name' ], $action[ 'name' ] );
					}
				}
			}
		}
	}

	/**
	 * Process an action
	 *
	 * @since 3.0
	 */
	public function execute_action() {
		# Determine the current hook/filter we're acting upon & get our options to act
		$hook = current_filter();
		$options = $this->get_active_actions();

		# Bail out if we have neither a hook nor options to work with
		if( ! $hook || ! $options )
			return;

		# Nice names for our options
		$action = $options[ $hook ][ 'action' ];
		$php = isset( $options[ $hook ][ 'php' ] ) ? 1 : 0;
		$shortcodes = isset( $options[ $hook ][ 'shortcodes' ] ) ? 1 : 0;

		# Process shortcodes if needed
		$value = $shortcodes ? do_shortcode( $action ) : $action;

		# Output our action, with or w/o PHP as needed
		if ( $php )
			eval( "?>$value<?php " );
		else
			echo $value;
	}

	/**
	 * Install OpenBox to allow custom code in Thesis 2
	 *
	 * @since 3.4
	 */
	private function install_openbox() {
		include_once( ABSPATH . '/wp-admin/includes/file.php' );

		if ( get_filesystem_method() === 'direct' ) {
			# Use the WordPress file management system to manage file manipulations
			WP_Filesystem();

			$f = $GLOBALS[ 'wp_filesystem' ];

			# Users may opt to install the OpenBox even without Thesis 2 installed;
			# in that situation, we'll need to ensure the whole file structure is created.
			if ( ! is_dir( WP_CONTENT_DIR . '/thesis/boxes/openbox' ) ) {
				$directories = array( 'thesis/', 'thesis/boxes/', 'thesis/boxes/openbox/' );

				# Loop the file structure, creating directories which do not exist
				foreach ( $directories as $dir )
					$f->mkdir( $f->wp_content_dir() . $dir );
			}

			# This installs the OpenBox file itself
			$f->put_contents( $f->wp_content_dir() . 'thesis/boxes/openbox/box.php', file_get_contents( OPENHOOK_PLUGIN_DIR . 'openbox.inc' ) );
		}
	}

	/**
	 * Remove OpenBox
	 *
	 * Thus far, this is an unused function. Deleting boxes removes them from Thesis' system,
	 * resulting in the saved customizations being lost with them. That's bad.
	 * This means that OpenBox persists, even after OpenHook is uninstalled. This function
	 * persists as a reminder that a workaround should be found someday. Someday.
	 *
	 * @since 3.4
	 */
	private function uninstall_openbox() {
		include_once( ABSPATH . '/wp-admin/includes/file.php' );

		if ( get_filesystem_method() === 'direct' ) {
			# Use the WordPress file management system to manage file manipulations
			WP_Filesystem();

			$f = $GLOBALS[ 'wp_filesystem' ];

			$f->rmdir( $f->wp_content_dir() . 'thesis/boxes/openbox/' );
		}
	}

	/**
	 * Return all active hooks as an array
	 *
	 * @return array Concatenated list of all active hooks
	 * @since 4.0
	 */
	private function get_active_hooks() {
		$options = get_option( 'openhook_general' );
		$active_groups = isset( $options[ 'active_actions' ] ) ? $options[ 'active_actions' ] : '';
		
		$headway_hooks = ( isset( $active_groups[ 'openhook_headway' ] ) && $active_groups[ 'openhook_headway' ] ) ? $this->headway_hooks() : array();
		$thesis_hooks = ( isset( $active_groups[ 'openhook_thesis' ] ) && $active_groups[ 'openhook_thesis' ] ) ? $this->thesis_hooks() : array();
		$wordpress_hooks = ( isset( $active_groups[ 'openhook_wordpress' ] ) && $active_groups[ 'openhook_wordpress' ] ) ? $this->wordpress_hooks() : array();
		$hooks = array_merge( (array) $headway_hooks, (array) $thesis_hooks, (array) $wordpress_hooks );

		return $hooks;
	}

	/**
	 * Return all actions to be processed
	 *
	 * @return array Concatenated list of all relevant options
	 * @since 3.0
	 */
	private function get_active_actions() {
		$options = get_option( 'openhook_general' );
		$active_groups = isset( $options[ 'active_actions' ] ) ? $options[ 'active_actions' ] : '';
		$active_actions = array();
		$groups = array( 'headway', 'thesis', 'wordpress' );

		foreach ( $groups as $group ) {
			if ( isset( $active_groups[ "openhook_$group" ] ) && $active_groups[ "openhook_$group" ] ) {
				$group_actions = get_option( "openhook_$group" );

				$active_actions = is_array( $group_actions ) ? array_merge( $active_actions, $group_actions ) : $active_actions;
			}
		}

		return $active_actions;
	}

	/**
	 * An array of available Headway hooks
	 *
	 * @link http://codex.headwaythemes.com/index.php?title=Hooks_%28Actions_and_Filters%29_List&oldid=951
	 * @since 4.0
	 */
	public function headway_hooks() {
		$hooks = array(
			'headway_after_block' => array(
				'name' => 'headway_after_block',
			),
			'headway_after_entry' => array(
				'name' => 'headway_after_entry',
			),
			'headway_after_entry_comments' => array(
				'name' => 'headway_after_block_comments',
			),
			'headway_after_entry_content' => array(
				'name' => 'headway_after_entry_content',
			),
			'headway_after_entry_title' => array(
				'name' => 'headway_after_entry_title',
			),
			'headway_after_footer' => array(
				'name' => 'headway_after_footer',
			),
			'headway_after_header_link' => array(
				'name' => 'headway_after_header_link',
			),
			'headway_after_tagline' => array(
				'name' => 'headway_after_tagline',
			),
			'headway_after_wrapper' => array(
				'name' => 'headway_after_wrapper',
			),
			'headway_before_block' => array(
				'name' => 'headway_before_block',
			),
			'headway_before_entry' => array(
				'name' => 'headway_before_entry',
			),
			'headway_before_entry_comments' => array(
				'name' => 'headway_before_entry_comments',
			),
			'headway_before_entry_content' => array(
				'name' => 'headway_before_entry_content',
			),
			'headway_before_entry_title' => array(
				'name' => 'headway_before_entry_title',
			),
			'headway_before_footer' => array(
				'name' => 'headway_before_footer',
			),
			'headway_before_header_link' => array(
				'name' => 'headway_before_header_link',
			),
			'headway_before_wrapper' => array(
				'name' => 'headway_before_wrapper',
			),
			'headway_block_close' => array(
				'name' => 'headway_block_close',
			),
			'headway_block_content_close' => array(
				'name' => 'headway_block_content_close',
			),
			'headway_block_content_open' => array(
				'name' => 'headway_block_content_open',
			),
			'headway_block_open' => array(
				'name' => 'headway_block_open',
			),
			'headway_body_close' => array(
				'name' => 'headway_body_close',
			),
			'headway_body_open' => array(
				'name' => 'headway_body_open',
			),
			'headway_entry_close' => array(
				'name' => 'headway_entry_close',
			),
			'headway_entry_open' => array(
				'name' => 'headway_entry_open',
			),
			'headway_footer_close' => array(
				'name' => 'headway_footer_open',
			),
			'headway_head_extras' => array(
				'name' => 'headway_head_extras',
			),
			'headway_html_close' => array(
				'name' => 'headway_html_close',
			),
			'headway_html_open' => array(
				'name' => 'headway_html_open',
			),
			'headway_page_start' => array(
				'name' => 'headway_page_start',
			),
			'headway_register_elements' => array(
				'name' => 'headway_register_elements',
			),
			'headway_scripts' => array(
				'name' => 'headway_scripts',
			),
			'headway_seo_meta' => array(
				'name' => 'headway_seo_meta',
			),
			'headway_setup' => array(
				'name' => 'headway_setup',
			),
			'headway_setup_child_theme' => array(
				'name' => 'headway_setup_child_theme',
			),
			'headway_stylesheets' => array(
				'name' => 'headway_stylesheets',
			),
			'headway_whitewrap_close' => array(
				'name' => 'headway_whitewrap_close',
			),
			'headway_whitewrap_open' => array(
				'name' => 'headway_whitewrap_open',
			),
			'headway_wrapper_close' => array(
				'name' => 'headway_wrapper_close',
			),
			'headway_wrapper_open' => array(
				'name' => 'headway_wrapper_open',
			),
		);

		return $hooks;
	}

	/**
	 * An array of available WordPress hooks
	 *
	 * @since 3.0
	 */
	public function thesis_hooks() {
		$hooks = array(
			'thesis_hook_before_html' => array(
				'name' => 'thesis_hook_before_html',
			),
			'thesis_hook_after_html' => array(
				'name' => 'thesis_hook_after_html',
			),
			'thesis_hook_before_header' => array(
				'name' => 'thesis_hook_before_header',
				'unhook' => array(
					'thesis_nav_menu' => array(
						'name' => 'thesis_nav_menu',
						'desc' => __( 'The default navigation menu', 'openhook' ),
					),
				),
			),
			'thesis_hook_after_header' => array(
				'name' => 'thesis_hook_after_header',
			),
			'thesis_hook_header' => array(
				'name' => 'thesis_hook_header',
				'unhook' => array(
					'thesis_default_header' => array(
						'name' => 'thesis_default_header',
						'desc' => __( 'Default header', 'openhook' ),
					),
				),
			),
			'thesis_hook_before_title' => array(
				'name' => 'thesis_hook_before_title',
			),
			'thesis_hook_after_title' => array(
				'name' => 'thesis_hook_after_title',
			),
			'thesis_hook_first_nav_item' => array(
				'name' => 'thesis_hook_first_nav_item',
			),
			'thesis_hook_last_nav_item' => array(
				'name' => 'thesis_hook_last_nav_item',
			),
			'thesis_hook_before_content_box' => array(
				'name' => 'thesis_hook_before_content_box',
			),
			'thesis_hook_after_content_box' => array(
				'name' => 'thesis_hook_after_content_box',
			),
			'thesis_hook_content_box_top' => array(
				'name' => 'thesis_hook_content_box_top',
			),
			'thesis_hook_content_box_bottom' => array(
				'name' => 'thesis_hook_content_box_bottom',
			),
			'thesis_hook_before_content' => array(
				'name' => 'thesis_hook_before_content',
			),
			'thesis_hook_after_content' => array(
				'name' => 'thesis_hook_after_content',
				'unhook' => array(
					'thesis_post_navigation' => array(
						'name' => 'thesis_post_navigation',
						'desc' => __( 'Displays links to the next or previous post', 'openhook' ),
					),
					'thesis_prev_next_posts' => array(
						'name' => 'thesis_prev_next_posts',
						'desc' => __( 'Displays links to the next or previous pages of an archive listing', 'openhook' ),
					),
				),
			),
			'thesis_hook_before_content_area' => array(
				'name' => 'thesis_hook_before_content_area',
			),
			'thesis_hook_after_content_area' => array(
				'name' => 'thesis_hook_after_content_area',
			),
			'thesis_hook_feature_box' => array(
				'name' => 'thesis_hook_feature_box',
			),
			'thesis_hook_before_post_box' => array(
				'name' => 'thesis_hook_before_post_box',
			),
			'thesis_hook_after_post_box' => array(
				'name' => 'thesis_hook_after_post_box',
			),
			'thesis_hook_after_content_box' => array(
				'name' => 'thesis_hook_after_content_box',
			),
			'thesis_hook_post_box_top' => array(
				'name' => 'thesis_hook_post_box_top',
			),
			'thesis_hook_post_box_bottom' => array(
				'name' => 'thesis_hook_post_box_bottom',
			),
			'thesis_hook_before_teasers_box' => array(
				'name' => 'thesis_hook_before_teasers_box',
			),
			'thesis_hook_after_teasers_box' => array(
				'name' => 'thesis_hook_after_teasers_box',
			),
			'thesis_hook_before_post' => array(
				'name' => 'thesis_hook_before_post',
			),
			'thesis_hook_after_post' => array(
				'name' => 'thesis_hook_after_post',
				'unhook' => array(
					'thesis_post_tags' => array(
						'name' => 'thesis_post_tags',
						'desc' => __( 'Displays a list of tags applied to the post', 'openhook' ),
					),
					'thesis_comments_link' => array(
						'name' => 'thesis_comments_link',
						'desc' => __( 'Displays a link to the comments of a post', 'openhook' ),
					),
				),
			),
			'thesis_hook_before_teaser_box' => array(
				'name' => 'thesis_hook_before_teaser_box',
			),
			'thesis_hook_after_teaser_box' => array(
				'name' => 'thesis_hook_after_teaser_box',
			),
			'thesis_hook_before_teaser' => array(
				'name' => 'thesis_hook_before_teaser',
			),
			'thesis_hook_after_teaser' => array(
				'name' => 'thesis_hook_after_teaser',
			),
			'thesis_hook_before_headline' => array(
				'name' => 'thesis_hook_before_headline',
			),
			'thesis_hook_after_headline' => array(
				'name' => 'thesis_hook_after_headline',
			),
			'thesis_hook_before_teaser_headline' => array(
				'name' => 'thesis_hook_before_teaser_headline',
			),
			'thesis_hook_after_teaser_headline' => array(
				'name' => 'thesis_hook_after_teaser_headline',
			),
			'thesis_hook_byline_item' => array(
				'name' => 'thesis_hook_byline_item',
			),
			'thesis_hook_before_comment_meta' => array(
				'name' => 'thesis_hook_before_comment_meta',
			),
			'thesis_hook_after_comment_meta' => array(
				'name' => 'thesis_hook_after_comment_meta',
			),
			'thesis_hook_after_comment' => array(
				'name' => 'thesis_hook_after_comment',
			),
			'thesis_hook_after_comments' => array(
				'name' => 'thesis_hook_after_comments',
			),
			'thesis_hook_comment_form_top' => array(
				'name' => 'thesis_hook_comment_form_top',
			),
			'thesis_hook_comment_field' => array(
				'name' => 'thesis_hook_comment_field',
			),
			'thesis_hook_after_comment_box' => array(
				'name' => 'thesis_hook_after_comment_box',
				'unhook' => array(
					'show_subscription_checkbox' => array(
						'name' => 'show_subscription_checkbox',
						'desc' => __( 'The Subscribe to Comments plugin subscription checkbox', 'openhook' ),
					),
				),
			),
			'thesis_hook_comment_form_bottom' => array(
				'name' => 'thesis_hook_comment_form_bottom',
			),
			'thesis_hook_archives_template' => array(
				'name' => 'thesis_hook_archives_template',
				'unhook' => array(
					'thesis_archives_template' => array(
						'name' => 'thesis_archives_template',
						'desc' => __( 'Default output for the archives template', 'openhook' ),
					),
				),
			),
			'thesis_hook_custom_template' => array(
				'name' => 'thesis_hook_custom_template',
				'unhook' => array(
					'thesis_custom_template_sample' => array(
						'name' => 'thesis_custom_template_sample',
						'desc' => __( 'Default sample output for the custom template', 'openhook' ),
					),
				),
			),
			'thesis_hook_faux_admin' => array(
				'name' => 'thesis_hook_faux_admin',
			),
			'thesis_hook_404_title' => array(
				'name' => 'thesis_hook_404_title',
				'unhook' => array(
					'thesis_404_title' => array(
						'name' => 'thesis_404_title',
						'desc' => __( 'Displays default 404 error page title', 'openhook' ),
					),
				),
			),
			'thesis_hook_404_content' => array(
				'name' => 'thesis_hook_404_content',
				'unhook' => array(
					'thesis_404_content' => array(
						'name' => 'thesis_404_content',
						'desc' => __( 'Displays default 404 error page content', 'openhook' ),
					),
				),
			),
			'thesis_hook_before_sidebars' => array(
				'name' => 'thesis_hook_before_sidebars',
			),
			'thesis_hook_after_sidebars' => array(
				'name' => 'thesis_hook_after_sidebars',
			),
			'thesis_hook_multimedia_box' => array(
				'name' => 'thesis_hook_multimedia_box',
			),
			'thesis_hook_after_multimedia_box' => array(
				'name' => 'thesis_hook_after_multimedia_box',
			),
			'thesis_hook_before_sidebar_1' => array(
				'name' => 'thesis_hook_before_sidebar_1',
			),
			'thesis_hook_after_sidebar_1' => array(
				'name' => 'thesis_hook_after_sidebar_1',
			),
			'thesis_hook_before_sidebar_2' => array(
				'name' => 'thesis_hook_before_sidebar_2',
			),
			'thesis_hook_after_sidebar_2' => array(
				'name' => 'thesis_hook_after_sidebar_2',
			),
			'thesis_hook_before_footer' => array(
				'name' => 'thesis_hook_before_footer',
			),
			'thesis_hook_after_footer' => array(
				'name' => 'thesis_hook_after_footer',
			),
			'thesis_hook_footer' => array(
				'name' => 'thesis_hook_footer',
				'unhook' => array(
					'thesis_attribution' => array(
						'name' => 'thesis_attribution',
						'desc' => __( 'Displays DIYthemes attribution &amp; link', 'openhook' ),
					),
				),
			),
		);

		return $hooks;
	}

	/**
	 * An array of available WordPress hooks
	 *
	 * @since 3.0
	 */
	public function wordpress_hooks() {
		$hooks = array(
			'wp_head' => array(
				'name' => 'wp_head',
				'desc' => __( 'Executes within your site&rsquo;s <code>head</code> section. Useful for outputting, for example, meta tags.', 'openhook' ),
				'unhook' => array(
					'feed_links' => array(
						'name' => 'feed_links',
						'desc' => __( 'Many themes support the automatic addition of <code>link</code> tags pointing to your site&rsquo;s primary &amp; comments feeds. Disable to prevent this behavior.', 'openhook' ),
						'priority' => 2,
					),
					'feed_links_extra' => array(
						'name' => 'feed_links_extra',
						'desc' => __( 'Similar to <code>feed_links</code>, except the <code>link</code> tags output by this hook point to secondary feeds, such as category feeds.', 'openhook' ),
						'priority' => 3,
					),
					'wp_enqueue_scripts' => array(
						'name' => 'wp_enqueue_scripts',
						'desc' => __( 'Is used to queue &amp; output various scripts. Disabling this prevents most scripts from appearing in your header, allowing you to manage them all manually, if you so choose.', 'openhook' ),
						'priority' => 1,
						'advanced' => 1,
					),
					'wp_generator' => array(
						'name' => 'wp_generator',
						'desc' => sprintf( __( 'Is used to output a <code>meta</code> tag crediting WordPress as the software powering your site. Removing this is a form of <a href="%s">security through obscurity</a>.', 'openhook' ), 'http://en.wikipedia.org/wiki/Security_through_obscurity' ),
					), 
				),
			),
			'wp_footer' => array(
				'name' => 'wp_footer',
				'desc' => __( 'Executed either within the footer section (<code>div#footer</code> or <code>footer</code>) or immediately before the closing <code>html</code> tag, this hook is commonly used to output stats tracking scripts or other asyncronously loaded JavaScript.', 'openhook' ),
				'unhook' => array(
					'wp_print_footer_scripts' => array(
						'name' => 'wp_print_footer_scripts',
						'desc' => __( 'Is used to queue &amp; output barious scripts. Disabling this prevents most scripts from appearing in your footer, allowing you to manage them all manually, if you so choose.', 'openhook' ),
						'priority' => 20,
						'advanced' => 1,
					),
				),
			),
		);

		return $hooks;
	}
}

# Processes OpenHook in the global domain
$openhook = new OpenHook();