<?php

// Create a navigation that is scalable

/*
 * Creating a sidebar location
 */

function ona_code_sidebar_menu() {
	register_nav_menu('code-sidebar',__( 'Ethics Code Menu' ));
}
add_action( 'init', 'ona_code_sidebar_menu' );


/*
 * Generate the sidebar
 */

function ona_create_menu(){
	$menu_args = array(
		'theme_location'  => 'code-sidebar',
		'menu'            => '', 
		'container'       => 'ul', 
		'container_class' => 'menu-{menu slug}-container', 
		'container_id'    => '',
		'menu_class'      => 'parent', 
		'menu_id'         => '',
		'echo'            => false,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'           => 0,
		'walker'          => new description_walker(),
		'current_user'	  => get_current_user_id()
	);
	return wp_nav_menu( $menu_args );
}

function ona_make_sidebar(){
	global $wp;
	$wp->q_sidebar = ona_create_menu();
}
add_action('wp_head', 'ona_make_sidebar');


/*
 * New walker for question sidebar
 */

class description_walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth, $args) {
		global $wp_query, $wp, $categories;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		// Custom links are used as plain text section titles
		if ( $item->type == 'custom'){
			$output .= '<div>'.$item->title.'</div>';

			// Generates an array of categories
	        if (!$wp->categories) $wp->categories = array();
			$wp->categories[$item->object_id] = array(
				'title' => $item->title
				);
			return;
		}

		$postID	  = $item->object_id;
		$thisUser = $args->current_user;
		$class_names = $value = '';

        $filteredTitle = ( strpos($item->title, 'Best Practices') || strpos($item->title, 'Best practices') ? 'Best Practices' : $item->title );
        $filteredTitle = ( strpos($filteredTitle, 'Ethical Choices') || strpos($filteredTitle, 'Ethical choices') ? 'Ethical Choices' : $filteredTitle );
        $filteredTitle = apply_filters( 'the_title', $filteredTitle, $item->ID );

        // Generates an array of the menu
        if (!$wp->steps) $wp->steps = array();
		$wp->steps[$postID] = array(
			'name' => __($filteredTitle),
			'parent' => ( $item->menu_item_parent ? $item->menu_item_parent : false),
			'menu_order' => $item->menu_order,
			'level' => $depth
			);
		if ( $depth == 1 ) {
			if ( !isset( $wp->categories[$item->menu_item_parent]['first_child'] )){
				$wp->categories[$item->menu_item_parent]['first_child'] = $postID;
				$wp->steps[$postID]['first'] = true;
			}
		}

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';

		$question_data = get_user_meta( $thisUser, 'question'.$postID, true);
		$answered = ($question_data?true:false);

		if ($item->current) { 
			echo '<style>#menu-item-'.$item->menu_item_parent.' .sub-menu { display:block; }</style>';
		}
  
        $output .= $indent . '<li class="answered'.($item->current?' current':'').'" id="menu-item-'.$item->ID.'">
       		<span class="demo-icon '.($item->current?'icon-right':($question_data?'icon-check-1':'')).'"></span>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$prepend = '';
		$append  = '';
		$description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

        if($depth != 0){
        	$description = $append = $prepend = "";
        }

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before .$prepend.$filteredTitle.$append;
        $item_output .= $description.$args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

?>
