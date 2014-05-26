<?php
/**
 * Plugin Name: FAQ's
 * Plugin URI: http://www.ryanhertz.com
 * Description: Custom Post Type Template
 * Version: 0.1
 * Author: Ryan Hertz
 * Author URI: http://www.ryanhertz.com
 * License: GPL2
 */

class QuickCustomPostType {

	public function __construct( $post_type_name, $singular_display_name ) {

		$this->post_type_name = $post_type_name;
		$this->singular_display_name = $singular_display_name;

		// adding the function to the Wordpress init
		add_action( 'init', array( $this, 'create_custom_post_type' ) );

		register_activation_hook( __FILE__, array( $this, 'my_rewrite_flush' ) );

		// add the template file
		add_filter('template_include', array( $this, 'custom_post_template' ) );
	}

	// Flush your rewrite rules
	public function my_flush_rewrite_rules() {
		flush_rewrite_rules();
	}

	function my_rewrite_flush() {
	    // First, we "add" the custom post type via the above written function.
	    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
	    // They are only referenced in the post_type column with a post entry, 
	    // when you add a post of this CPT.
	    $this->create_custom_post_type();

	    // ATTENTION: This is *only* done during plugin activation hook in this example!
	    // You should *NEVER EVER* do this on every page load!!
	    flush_rewrite_rules();
	}

	// let's create the function for the custom type
	public function create_custom_post_type() { 

		$ns = 						$this->post_type_name . '_namespace';
		$plural_display_name = 		$this->singular_display_name . '\'s';
		$all_plural_display_name = 'All ' . $plural_display_name;
		$singular_name = 			strtolower($this->singular_display_name);
		$plural_name = 				strtolower($this->singular_display_name . 's');
		$icon_path = 				'/' . $plural_name . '.png';

		// creating (registering) the custom type 
		register_post_type( $this->post_type_name, /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
			// let's now add all the options for this post type
			array( 'labels' => array(
					'name' => __( $plural_display_name, $ns ), /* This is the Title of the Group */
					'singular_name' => __( $this->singular_display_name, $ns ), /* This is the individual type */
					'all_items' => $all_plural_display_name, /* the all items menu item */
					'add_new' => __( 'Add New', $ns ), /* The add new menu item */
					'add_new_item' => __( 'Add New ' . $this->singular_display_name, $ns ), /* Add New Display Title */
					'edit' => __( 'Edit', $ns ), /* Edit Dialog */
					'edit_item' => __( 'Edit ' . $this->singular_display_name, $ns ), /* Edit Display Title */
					'new_item' => __( 'New ' . $this->singular_display_name, $ns ), /* New Display Title */
					'view_item' => __( 'View ' . $this->singular_display_name, $ns ), /* View Display Title */
					'search_items' => __( 'Search ' . $plural_display_name, $ns ), /* Search Custom Type Title */ 
					'not_found' =>  __( 'Nothing found in the Database.', $ns ), /* This displays if there are no entries yet */ 
					'not_found_in_trash' => __( 'Nothing found in Trash', $ns ), /* This displays if there is nothing in the trash */
					'parent_item_colon' => ''
				), /* end of arrays */
				'description' => __( $this->singular_display_name . ' List', $ns ), /* Custom Type Description */
				'public' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'show_ui' => true,
				'query_var' => true,
				'menu_position' => 9, /* this is what order you want it to appear in on the left hand side menu */ 
				'menu_icon' => plugins_url( $icon_path, __FILE__ ), /* the icon for the custom post type menu */
				'rewrite'	=> array( 'slug' => $plural_name, 'with_front' => false ), /* you can specify its url slug */
				'taxonomies' => array('category'),  /* this gives the post type categories */
				'has_archive' => true, /* optional, slug for archive page */
				'capability_type' => 'post',
				'hierarchical' => false,
				/* the next one is important, it tells what's enabled in the post editor */
				'supports' => array( 'title', 'editor', 'thumbnail', 'revisions')
				//'register_meta_box_cb' => 'add_custom_post_metaboxes'
			) /* end of options */
		); /* end of register post type */
		
	}

	/
	public function custom_post_template( $template ) {

		$singular_name = strtolower($this->singular_display_name);

	    $file_name = $singular_name . 's-template.php';

		if ( strpos( $_SERVER['REQUEST_URI'],  $singular_name ) !== false ) 
			$template = dirname(__FILE__) . '/' . $file_name;  

	    return $template;
	}
}


/*
To create an instance, you need to pass two paramaters.

$my_custom_post_type = new QuickCustomPostType( $post_type_name, $singular_display_name );

Example:
$faq_custom_post_type = new QuickCustomPostType( 'my_faqs', 'FAQ' );

This class will automatically look for a template file based on the $singular_display_name parameter.
If you pass 'FAQ', then the template file should be 'faqs-template.php'.

*/
?>