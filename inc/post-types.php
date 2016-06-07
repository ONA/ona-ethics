<?php

// Add a post type for questions

// Our custom post type function
function question_post_type() {

	register_post_type( 'question',
		array(
			'labels' => array(
				'name' 				  => __( 'Questions' ),
				'singular_name' 	  => __( 'Question' ),
				'menu_name'           => __( 'Questions', 'twentythirteen' ),
				'parent_item_colon'   => __( 'Parent Question', 'twentythirteen' ),
				'all_items'           => __( 'All Questions', 'twentythirteen' ),
				'view_item'           => __( 'View Question', 'twentythirteen' ),
				'add_new_item'        => __( 'Add New Question', 'twentythirteen' ),
				'add_new'             => __( 'Add New', 'twentythirteen' ),
				'edit_item'           => __( 'Edit Question', 'twentythirteen' ),
				'update_item'         => __( 'Update Question', 'twentythirteen' ),
				'search_items'        => __( 'Search Question', 'twentythirteen' ),
				'not_found'           => __( 'Not Found', 'twentythirteen' ),
				'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' )
			),

			'description'         => __( 'Movie news and reviews', 'twentythirteen' ),
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'editor' ),
			// You can associate this CPT with a taxonomy or custom taxonomy. 
			'taxonomies'          => array( 'genres' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/	
			'has_archive' 		  => false,
			'hierarchical'        => false,
			'public'              => true,
			'rewrite' 			  => array('slug' => 'q'),
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'public'			  => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page'
		)
	);
}
add_action( 'init', 'question_post_type' );

?>
