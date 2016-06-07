<?php

// Load the theme specific files
include get_template_directory() . '/inc/post-types.php';
include get_template_directory() . '/inc/meta-boxes.php';
include get_template_directory() . '/inc/registration.php';
include get_template_directory() . '/inc/menus-sidebars.php';




// ------------------------
// Enqueue Javascript files
function ethics_load_javascript_files() {
	if ( !is_admin() ){
		wp_register_script( 'ona-ethics', get_template_directory_uri().'/scripts.js', array('jquery'), '1.1', true );
		wp_enqueue_script( 'ona-ethics' );
	}
}
add_action( 'wp_enqueue_scripts', 'ethics_load_javascript_files' );

// --------------
// Enqueue styles
function ethics_load_style() {
	if ( !is_admin() )
	    wp_register_style('ethics_googleFonts',  '//fonts.googleapis.com/css?family=Khula:300,500,700|Halant:300,600' );
		wp_register_style('ethics_style', get_stylesheet_uri() );
		wp_register_style('fontello', get_template_directory_uri().'/css/fontello.css' );
	    wp_enqueue_style( 'ethics_googleFonts' );
	    wp_enqueue_style( 'ethics_style' );
	    wp_enqueue_style( 'fontello' );
}
add_action('wp_print_styles', 'ethics_load_style');

// -------------
// Register user
function register_user(){
	if ( isset( $_POST['register_user'] ) ){
		$display_name = $_POST['first_name'].' '.$_POST['last_name'];
		$fields = array( 
			'role' 			=> 'subscriber',
			'first_name' 	=> $_POST['first_name'],
			'last_name' 	=> $_POST['last_name'],
			'nickname' 		=> $display_name,
			'display_name' 	=> $display_name,
			'user_login'	=> '',
			'user_pass'		=> ''
			);
		$user_id = wp_insert_user( $fields ) ;
		// On success
		if( !is_wp_error($user_id) ) {

		}
		update_user_meta( $user_id, 'employer', $_POST['employer'] );
	}
}
add_action( 'wp_head', 'register_user' );


// ---------------------------
// Process question submission

function question_answer(){
	$qID = $_POST['qID'];
	if ( isset( $_POST['question_response'] ) ){
		// We want to make sure the last response becomes multiple items
		$openEnd = array_pop($_POST['question_response']);
		$openEnd = trim($openEnd);
		$openArray = explode("\n", $openEnd);
		$openArray = array_filter($openArray, 'trim');
		$response = array_merge($_POST['question_response'], $openArray);

	} else {
		$response = array();
	}
	update_user_meta( get_current_user_id(), 'question'.$qID, $response);
}
add_action( 'wp_head', 'question_answer' );




// -----------------
// Custom login page

function ethics_login( $force_reauth, $redirect ){
	$login_url = site_url().'/login';
	return $login_url ;
}
add_filter( 'login_url', 'ethics_login', 10, 2);


// ---------------------------
// On login, go to ethics code

function ethics_login_redirect() {
	return site_url().'/dashboard';
}
add_filter('login_redirect', 'ethics_login_redirect');


// --------------------------
// Remove admin bar for users

function ethics_bar_bump() {
	if ( !current_user_can( 'edit_posts' ) ){
		remove_action('wp_head', 'ONA_new_margin_top'); // replaces _admin_bar_bump_cb
	}
}
function ethics_admin_bar(){	
	if ( !current_user_can( 'edit_posts' ) ){
		add_filter( 'show_admin_bar', '__return_false' );
	}
}
add_action( 'init', 'ethics_admin_bar' , 9 );
add_action( 'get_header', 'ethics_bar_bump');


// ----------------------------------------
// Ability to shortcode a page/post content

function ethics_content( $atts ) {

	// Attributes
	$a = shortcode_atts(
		array(
			'type' => 'post',
			'id' => false,
		), $atts );

	if ($id = $a['id']){
		if ($a['type'] == 'post'){
			$content_post = get_post($id);
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			return $content;
		}
	}
}
add_shortcode( 'content', 'ethics_content' );


// -----------------------------
// Redirect on questions archive

function some_func( $query ){
    if ( is_post_type_archive('question') ) {
        wp_redirect( home_url() );
		exit;
    }
}
add_action('wp_head','some_func');


/**
 * Plugin Name: Multisite: Passwort Reset on Local Blog
 * Plugin URI:  https://gist.github.com/eteubert/293e07a49f56f300ddbb
 * Description: By default, WordPress Multisite uses the main blog for passwort resets. This plugin enables users to stay in their blog during the whole reset process.
 * Version:     1.0.0
 * Author:      Eric Teubert
 * Author URI:  http://ericteubert.de
 * License:     MIT
 */

// fixes "Lost Password?" URLs on login page
add_filter("lostpassword_url", function ($url, $redirect) {	
	
	$args = array( 'action' => 'lostpassword' );
	
	if ( !empty($redirect) )
		$args['redirect_to'] = $redirect;

	return add_query_arg( $args, site_url('wp-login.php') );
}, 10, 2);

// fixes other password reset related urls
add_filter( 'network_site_url', function($url, $path, $scheme) {
  
  	if (stripos($url, "action=lostpassword") !== false)
		return site_url('wp-login.php?action=lostpassword', $scheme);
  
   	if (stripos($url, "action=resetpass") !== false)
		return site_url('wp-login.php?action=resetpass', $scheme);
  
	return $url;
}, 10, 3 );

// fixes URLs in email that goes out.
add_filter("retrieve_password_message", function ($message, $key) {
  	return str_replace(get_site_url(1), get_site_url(), $message);
}, 10, 2);

// fixes email title
add_filter("retrieve_password_title", function($title) {
	return "[" . wp_specialchars_decode(get_option('blogname'), ENT_QUOTES) . "] Password Reset";
});


/* Get next in order */

function getNextEthic($id = false){
	global $wp;
	$steps = $wp->steps;
	if (!$id) return false;
	$keys = array_keys($steps);
	$current = array_search($id, $keys);
	$next = $keys[$current+1];
	return $next;
}

function fn_theme_options( $wp_customize ) {

  $wp_customize->add_section(
  'ethics_home_settings',
    array(
      'title' => __( 'Home Page Settings', 'ONA' ),
      'priority' => 100,
      'capability' => 'edit_theme_options',
      'description' => __('Change home page options here.', 'ONA'),
    )
  );

  /**
   * Home page kicker
   */

  $wp_customize->add_setting( 'ethics_tagline' , array(
    'default'     => 'test',
    'transport'   => 'refresh',
  ) );

  $wp_customize->add_control(
    'ethics_tagline_control',
    array(
      'section'   => 'ethics_home_settings',
      'label'     => 'Tagline',
      'type'      => 'text',
      'settings'  => 'ethics_tagline'
    )
  );

}
add_action( 'customize_register' , 'fn_theme_options' );

?>
