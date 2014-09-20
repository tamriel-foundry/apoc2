<?php 
/**
 * Apocrypha Theme Class
 * Andrew Clayton
 * Version 2.0
 * 4-29-2014
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
			
			// Register filters
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
		require( LIB_DIR . 'core/posts.php' );
		require( LIB_DIR . 'core/users.php' );
		require( LIB_DIR . 'core/widgets.php' );

		
		// Extensions
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
		
		// Populate the theme object after template redirect
		add_action( 'template_redirect' , array( $this , 'setup' ) , 1 );
	}
	
	/*-----------------------------------------------
		POPULATE THEME OBJECT
	-----------------------------------------------*/
	
	/**
	 * Populate the theme object
	 * @since 2.0
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