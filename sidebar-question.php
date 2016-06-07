<?php  $thisID = get_the_id();
	$answered = true; ?>

<div class="sidebar questions">

	<?php if ( get_the_title() == 'Edit' ) { ?>

	<h4>Your Account</h4>
	<ul class="parent">
		<li class="answered"><a href="<?=site_url()?>/dashboard">Dashboard</a></li>
		<li class="answered"><a href="<?=site_url()?>/your-ethics-code">Your Code</a></li>
		<li class="answered current">Edit Profile</li>
	</ul>

	<?php } else { ?>
		<h4>Ethical Issues</h4>
		<?php echo $wp->q_sidebar; ?>
	<?php } ?>
</div>
