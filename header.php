<!DOCTYPE html>

<html <?php language_attributes(); ?>>

	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >

		<title><?php wp_title('|', true, 'right'); ?><?php echo get_bloginfo( 'name' );?></title>

		<?php wp_head(); ?>

		<?php if ( !is_user_logged_in() && $_SERVER['SERVER_NAME'] == 'ethics.journalists.org') { ?>	
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-39011777-9', 'auto');
		  ga('send', 'pageview');
		</script>
		<?php } ?>

	</head>

	<body <?php body_class(); ?>>

		<nav>
			<a href="<?=site_url()?>" class="logo"></a>
			<div id="menu-toggle"><span>MENU</span></div>
			<ul>
				<li><a href="<?=site_url()?>/about">About</a></li>
				<? if ( is_user_logged_in() ){ ?>
				<li><a href="<?=site_url()?>/dashboard">Dashboard</a></li>
				<li><a href="<?=site_url()?>/your-ethics-code">Your Code</a></li>
				<li><a href="<?=site_url()?>/edit">Your Account</a></li>
				<li><a class="btn" href="<?=wp_logout_url( site_url() );?>">Log Out</a></li>
				<? } else { ?>
				<li><a class="btn" href="<?=site_url()?>/signup">Sign Up</a></li>
				<li><a class="btn" href="<?=site_url()?>/signin">Sign In</a></li>
				<? } ?>
			</ul>
		</nav>

		<div class="container">
