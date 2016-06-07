<?php 
// Redirect user if not logged in
if ( !is_user_logged_in() ){
	wp_redirect( home_url().'/signin/' );
	exit;
} ?>

<?php get_header(); ?>

<div id="question" class="main-column float">

	<h1>Edit your Account</h1>
	<p>You can update your account details below. Remember to update your changes.</p>
	<?php echo do_shortcode('[dm_registration_form]'); ?>

</div>

<?php get_sidebar('question'); ?>
<?php get_footer(); ?>
