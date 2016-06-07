<?php if ( is_user_logged_in() ){
	wp_redirect( site_url().'/your-ethics-code/' );
	exit;
}?>

<?php get_header(); ?>

<h1>Sign In to Your Account</h1>
<p>Save your progress and your settings in order to easily revise your ethics code in the future.</p>

<form name="login-form" class="standard-form" action="../wp-login.php" method="post">
    <label for="login"><?php _e( 'Email' ); ?></label>
    <input type="text" name="log" id="login" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" tabindex="97" />
    <label for="pwd"><?php _e( 'Password' ); ?></label>
    <input type="password" name="pwd" id="pwd" tabindex="98" />
    <div class="submit">
    	<input type="submit" name="wp-submit" class="btn" value="<?php _e( 'Log In' ); ?>" tabindex="100"  />
    </div>
</form>
<a href="<?php echo wp_lostpassword_url( get_bloginfo('url') ); ?>" title="Lost Password">Lost Password</a>

<?php get_footer(); ?>
