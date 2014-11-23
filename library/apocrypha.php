<?php 
/**
 * Apocrypha Theme Class
 * Andrew Clayton
 * Version 2.0
 * 9-20-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * The Apocrypha theme class
 * Constsructed as a singleton instance after WordPress initialization.
 * Contains WordPress modifications, inclusions, and extensions.
 *
 * @since 2.0
 */
class Apocrypha {

	/*-----------------------------------------------
		CONSTRUCT OBJECT
	-----------------------------------------------*/
	
	// Dummy constructors prevent multiple instances
	private static $instance = NULL;
	private function __construct() {}
	private function __clone() {}

	/**
	 * The single Apocrypha instance.
	 * Checks whether the theme has already been initialized before proceeding.
	 *
	 * @since 2.0
	 */
	public static function instance() {
		
		// Only construct once
		if ( !isset( self::$instance ) ) {
		
			// Instantiate the class
			self::$instance = new Apocrypha;
			
			// Define theme constants
			self::$instance->constants();
			
			// Include theme files
			self::$instance->includes();
			
			// Hook actions
			self::$instance->actions();
		}
		
		// Return the theme class
		return self::$instance;	
	}

	/*-----------------------------------------------
		DEFINE CONSTANTS
	-----------------------------------------------*/	
	
	/**
	 * Define theme constants and supports.
	 * @since 2.0
	 */
	private function constants() {
	
		// Core constants
		define( 'SITENAME' 			, get_bloginfo( 'name' ) );
		define( 'SITEURL' 			, get_home_url() );
		define( 'THEME_DIR' 		, get_template_directory() );
		define( 'THEME_URI' 		, get_template_directory_uri() );
		define( 'LIB_DIR' 			, trailingslashit( THEME_DIR ) . 'library/' );
		
		// Apocrypha theme supports
		add_theme_support( 'html5' );
		add_theme_support( 'bbpress' );
		add_theme_support( 'buddypress' );
		add_theme_support( 'post-thumbnails' );
		
		// Theme does not use admin bar
		show_admin_bar( false );
		add_filter( 'show_admin_bar', '__return_false' );

	}
	
	
	/*-----------------------------------------------
		INCLUDE FILES
	-----------------------------------------------*/
	private function includes() {

		// Core Functions
		require( LIB_DIR . 'core/ajax.php' );
		require( LIB_DIR . 'core/breadcrumbs.php' );
		require( LIB_DIR . 'core/core.php' );
		require( LIB_DIR . 'core/comments.php' );
		require( LIB_DIR . 'core/context.php' );
		require( LIB_DIR . 'core/login.php' );
		require( LIB_DIR . 'core/posts.php' );
		require( LIB_DIR . 'core/users.php' );
		require( LIB_DIR . 'core/widgets.php' );

		// Extensions
		require( LIB_DIR . 'extensions/entropy-rising.php' );
		require( LIB_DIR . 'extensions/events.php' );
		require( LIB_DIR . 'extensions/search.php' );
		require( LIB_DIR . 'extensions/shortcodes.php' );
		require( LIB_DIR . 'extensions/slides.php' );
		require( LIB_DIR . 'extensions/thumbnail.php' );
		require( LIB_DIR . 'map/map.php' );
		
		// Plugin Supports
		if ( class_exists( 'bbPress' ) ) 
			require( LIB_DIR . 'bbpress/bbpress.php' );
		if ( class_exists( 'BuddyPress' ) )	
			require( LIB_DIR . 'buddypress/buddypress.php' );		
		
		// Admin Functions
		if ( is_admin() ) 
			require( LIB_DIR . 'admin/posts.php' );
	}
	
	
	/*-----------------------------------------------
		HOOK THEME ACTIONS
	-----------------------------------------------*/	
	private function actions() {
	
		// Strip WordPress header bloat
		remove_action( 'wp_head' , 'wp_generator' );
		remove_action( 'wp_head' , 'wlwmanifest_link' );
		remove_action( 'wp_head' , 'rsd_link' );
		remove_action( 'wp_head' , 'rel_canonical' );
		remove_action( 'wp_head' , 'wp_shortlink_wp_head' );
		
		// Populate the theme object before anything on the page is loaded
		add_action( 'get_header' , array( $this , 'setup' ) , 1 );

		// Initialize special Admin rules
		add_action( 'admin_init' , array( $this , 'init_admin' ) );
	}
	
	/*-----------------------------------------------
		POPULATE THEME OBJECT
	-----------------------------------------------*/
	
	/**
	 * Populate the theme object
	 * @version 2.0
	 */
	public function setup() {
	
		// Basic site information
		$this->site				= SITENAME;
		$this->version			= THEMEVER;
	
		// Current user information
		$this->mobile			= wp_is_mobile();
		$this->user				= wp_get_current_user();
		
		// Current page information
		$context				= new Apoc_Context();
		$this->title			= $context->title;
		$this->description		= $context->description;
		$this->url				= $context->url;
		$this->classes			= $context->classes;
		$this->crumbs			= $context->crumbs;
		$this->page				= $context->page;
		$this->search			= $context->search;
	}

	/*-----------------------------------------------
		ADMINISTRATION CONFIGURATION
	-----------------------------------------------*/

	/**
	 * Admin initialization actions
	 * @version 2.0
	 */
	function init_admin() {
	
		// Stop normal users from accessing the admin panel except for AJAX requests
		if ( !current_user_can( 'publish_posts' ) &&  !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			wp_redirect( SITEURL ); 
			exit;
		}
		
		// Deregister the wordpress heartbeat script except for editing new posts
		global $pagenow;
		if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow )
			wp_deregister_script('heartbeat');		
	}
}

/**
 * The Apocrypha access function.
 * Used to quickly access the singleton theme class.
 *
 * @since 2.0
 */
function apoc() {
	return Apocrypha::instance();
}

?>