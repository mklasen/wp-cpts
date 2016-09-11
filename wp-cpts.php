<?php
defined( 'ABSPATH' ) or die( 'You can\'t access this file directly!');
/**
 * Plugin Name: mklasen's wp-CPT
 * Plugin URI: http://plugins.mklasen.com/wp-cpt/
 * Description: Add easy Frequently Asked Cpts to your WordPress website. Answers are shown (slide-down) after a visitor clicks on a question.
 * Version: 1.0.2
 * Author: Marinus Klasen
 * Author URI: https://mklasen.com
 
	
	/* **************************
	#
	#  Register Post Type for CPT
	#
	*************************** */ 
	
	function register_post_type_cpt() {
	
		$labels = array(
			'name'               => _x( 'CPT'),
			'singular_name'      => _x( 'Cpt'),
			'menu_name'          => _x( 'CPT'),
			'name_admin_bar'     => _x( 'Cpt'),
			'add_new'            => _x( 'Add Cpt'),
			'add_new_item'       => __( 'Add New Cpt'),
			'new_item'           => __( 'New Cpt'),
			'edit_item'          => __( 'Edit Cpt'),
			'view_item'          => __( 'View Cpt'),
			'all_items'          => __( 'All Cpts'),
			'search_items'       => __( 'Search Cpts'),
			'parent_item_colon'  => __( 'Parent Cpt:'),
			'not_found'          => __( 'No cpts found.'),
			'not_found_in_trash' => __( 'No cpts found in Trash.'), 
		);
	
		$args = array(
			'labels'             => $labels,
			'taxonomies' 		 => array('cpt-category'),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'cpt' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 6,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'page-attributes' ),
			'menu_icon'			 => 'dashicons-admin-settings' // See available icons here: https://developer.wordpress.org/resource/dashicons/#admin-settings
		);
	
    // Actually register post type with the arguments/parameters above
		register_post_type( 'cpt', $args );
		
		// Register a taxonomy/category for the custom post type
		register_taxonomy(
			'cpt-category',
			'cpt',
			array(
				'label' => __( 'Categories' ),
				'rewrite' => array( 'slug' => 'category' ),
				'hierarchical' => true,
			)
		);
	}
	
	// Actually fire the action to register the post type in the init hook
	add_action('init', 'register_post_type_cpt');
	
	
	/* **************************
	#
	#  Include Styles and Scripts for Front-End
	#
	*************************** */ 
		
	function mklasens_cpt_enqueue() {
		wp_register_script('mklasens-cpt-js', plugins_url('assets/js/wp-cpts.js', __FILE__), array('jquery'), '', true);
		wp_register_style('mklasens-cpt-css', plugins_url('assets/js/wp-cpts.css', __FILE__), false);
		wp_enqueue_script('jquery');
		wp_enqueue_script('mklasens-cpt-js');
		wp_enqueue_style('mklasens-cpt-css');
	}
	
	add_action( 'wp_enqueue_scripts', 'mklasens_cpt_enqueue' );
	
	
	/* **************************
	#
	#  Front-end code when plugin shortcode is being used [cpt]
	#
	*************************** */ 
	
/*
	function mklasens_cpt( $atts ) {
      $atts = shortcode_atts( array(
 	      'category' => ''
      ), $atts );
      
      // Get items
		$args = array(
			'post_type' => 'cpt',
			'orderby'   => 'menu_order',
			'posts_per_page' => 9999,
			'post_parent' => 0,
			'order' => 'ASC',
			'cpt-category' => $atts['category']
		);
	
		$posts = get_posts($args);
		$output = '';
		$output .= '<div class="mklasens-cpt">';
			
		foreach($posts as $post) {
			$children = get_children(array(
				'order' => 'ASC',
				'post_parent' => $post->ID,
				'orderby'   => 'menu_order',
			));
			$output .= '<div class="parent-posts" data-id="'.$post->ID.'">';
			if (!empty($post->post_title)) {
				$output .= '<div data-id="'.$post->ID.'" class="question"><div class="icon"></div>'.$post->post_title.'</div>';
				if (!empty($post->post_content)) {
					$output .= '<div data-id="'.$post->ID.'" class="answer">'.$post->post_content.'</div>';
				}
			}
			foreach ($children as $child) {
				if (!empty($child->post_title)) {
				$output .= '<div class="child-posts">';
					$output .= '<div data-id="'.$child->ID.'" class="question"><div class="icon"></div>'.$child->post_title.'</div>';
					if (!empty($child->post_content)) {
						$output .= '<div data-id="'.$child->ID.'" class="answer">'.$child->post_content.'</div>';
					}
				$output .= '</div>';
				}
			}
			$output .='</div>';
		}
		$output .= '</div>';
		return $output;
	}
	add_shortcode( 'cpt', 'mklasens_cpt' );
*/


	
	/* **************************
	#
	#  Button for adding CPT to Page/Post
	#  - Add a button for adding a single portfolio item (or category) to a page or post
	#
	*************************** */ 
	
		
	/**
	 * Adds a box to the main column on the Post and Page edit screens.
	 */
/*
	function mklasens_cpt_media_button_metabox() {
	
		$screens = array( 'post', 'page' );
	
		foreach ( $screens as $screen ) {
	
			add_meta_box(
				'mklasen_add_cpt_content',
				__( 'Add CPT'),
				'mklasens_cpt_media_button_metabox_content',
				$screen
			);
		}
	}
	add_action( 'add_meta_boxes', 'mklasens_cpt_media_button_metabox' );
*/
	
	/* **************************
	#
	#  Content for the Add CPT button 
	#  - Modal for the user to specify what shortcode to "build"
	#
	*************************** */ 
	
/*
	function mklasens_cpt_media_button_metabox_content( $post ) {
		
		$cats = get_categories('taxonomy=cpt-category');
		
		echo '<div class="mklasens_select_cpt" id="mklasens_select_cpt" style="display: none;">';
			echo '<h1>Which CPT\'s would you like to show?</h1>';
			if (!$cats) {
				echo '<p>No CPT categories yet. Show all cpt\'s or <a href="edit-tags.php?taxonomy=cpt-category&post_type=cpt">make one</a></p>.';
			}
			echo '<select class="select_cpt_category">';
				echo '<option value="">Show all CPT\'s...</option>';
				if ($cats) {			
					foreach ($cats as $cat) {
						echo '<option value="'.$cat->name.'">'.$cat->name.'</option>';
					}
					
				}
			echo '</select>';
			submit_button('Add CPT', '', 'mklasen_submit_add_cpt');
		echo '</div>';
	}
*/
	
	
	/**
	 * 
	 * Simply include scripts to the admin
	 * 
	 */
	
	function mklasens_cpt_scripts() {
		wp_enqueue_script( 'mklasen-cpt-admin-js', plugin_dir_url( __FILE__ ) . '/assets/js/wp-cpts.admin.js', 'jquery', '', true );
		wp_enqueue_style( 'mklasen-cpt-admin-css', plugin_dir_url( __FILE__ ) . '/assets/js/wp-cpts.admin.css' );
	}
	
	add_action('admin_enqueue_scripts', 'mklasens_cpt_scripts');
	
	
	/**
	 * 
	 * Add media button for CPT
	 * 
	 */
		
	
/*
	function mklasens_cpt_media_button() {
		$screen = get_current_screen();
		if (isset($screen->parent_base) && $screen->parent_base != 'edit')
			return;
			
		        // do a version check for the new 3.5 UI
		        $version = get_bloginfo('version');
		        
		        if ($version < 3.5) {
		            // show button for v 3.4 and below
		            $image_btn = "notsetyet.png";
		            echo '<a href="#TB_inline?width=480&height=300&inlineId=mklasens_select_cpt" class="thickbox" id="add_wpdb_cpt" title="' . __("Add CPT", 'mklasen') . '"><img src="'.$image_btn.'" alt="' . __("Add CPT", 'mklasens-cpt-textdomain') . '" /></a>';
		        } else {
		            // display button matching new UI
		            echo '<style>
		            		.mklasens_cpt_media_icon  {
		            			display: inline-block;
								width: 18px;
								height: 18px;
								vertical-align: sub;
								margin: 0 2px;
							}
		                    span.mklasens_cpt_media_icon:before {
			                    font: 400 18px/1 dashicons;
								speak: none;
								-webkit-font-smoothing: antialiased;
								-moz-osx-font-smoothing: grayscale;
								content: "\f203";
								color: #888;
		                    }
		                 </style>
		                  <a href="#TB_inline?width=480&height=300&inlineId=mklasens_select_cpt" class="thickbox button mklasens_cpt_link" id="add_mklasens_cpt" title="' . __("Add CPT", 'mklasens-cpt-textdomain') . '"><span class="mklasens_cpt_media_icon "></span> ' . __("Add CPT", 'mklasens-cpt-textdomain') . '</a>';
		        }

	}
	add_action('media_buttons', 'mklasens_cpt_media_button', 15);
*/
	
	/**
	 * 
	 * Manage Columns
	 * 
	 */
		
	
/*
	add_filter( 'manage_edit-cpt_columns', 'set_custom_edit_cpt_columns' );
	
	add_action( 'manage_cpt_posts_custom_column' , 'custom_cpt_column', 10, 2 );
	
	function set_custom_edit_cpt_columns($columns) {
	    unset( $columns['author'] );
	    unset( $columns['comments'] );
	    unset( $columns['date'] );
		$columns['cpt-categories'] = __( 'Categories', 'your_text_domain' );
	
	    return $columns;
	}
	
	function custom_cpt_column( $column, $post_id ) {
	    switch ( $column ) {

        case 'cpt-categories' :
            $terms = get_the_term_list( $post_id , 'cpt-category' , '' , ',' , '' );
            if ( is_string( $terms ) )
                echo $terms;
            else
                _e( '- No category -', 'your_text_domain' );
            break;
	
	    }
	}
*/