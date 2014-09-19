<?php
/**
 * Apocrypha Interactive Map Functions
 * Andrew Clayton
 * Version 2.0
 * 9-19-2014
*/

/*---------------------------------------------
	1.0 - MAP CLASS
----------------------------------------------*/
 class Apoc_Map {
 
	// Declare variables
	public $version = 0.15;

	// Construct the class
	function __construct() {
		add_action( 'template_redirect', array( &$this , 'map_template' ) );
	}
	
	
	// Redirect the template to use the map template
	function map_template() {
		
		global $wp_query;
		if ( $wp_query->query_vars['pagename'] == 'map' ) {
			
			// Grab the template
			$this->setup_map();
			include ( THEME_DIR . '/library/map/interactive-map.php' );
			exit();
		}
	}
	
	/**
	 * Customize map view properties
	 */
	function setup_map() {
		
		// Set wp_query parameters
		global $wp_query;
		$wp_query->is_404 = false;
		$wp_query->is_map = true;
		
		// Set theme objects
		$apoc = apoc();
		$apoc->body_class = str_replace( 'error404' , 'singular page map' , $apoc->body_class );
		$apoc->title = "Interactive Map of Tamriel";
		$apoc->description = "A richly interactive map of the entirety of Tamriel which is available in The Elder Scrolls Online.";
		
		// Add custom scripts and styles
		add_action( 'wp_enqueue_scripts' , array( $this , 'enqueue_scripts' ) );
	}
	
	/**
	 * Add interactive map scripts and styles
	 */	
	function enqueue_scripts() {
		
		// Register
		wp_register_script( 'mapsapi' , 'http://maps.googleapis.com/maps/api/js?key=AIzaSyCOp6ztSZl-ZbiVkzq_5MabejnPgWbht8A&sensor=false' , 'jquery' , false , true );
		wp_register_script( 'esomap' , THEME_URI . '/library/map/map-control.js' , 'mapsapi' , $this->version , true	);
		wp_register_style( 'mapstyle' , THEME_URI . '/library/map/map-style.css' , 'primary' , $this->version , false );
		
		// Enqueue
		wp_enqueue_script( 'esomap' );
		wp_enqueue_script( 'mapsapi' );
		wp_enqueue_style( 'mapstyle' );
	}
	
}
$map = new Apoc_Map();

/*---------------------------------------------
	2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/
function is_interactive_map() {
	global $wp_query;
	return $wp_query->is_map;
}