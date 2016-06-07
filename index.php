<?php get_header(); ?>

<div id="main-logo"></div>
<?php if ( is_user_logged_in() ){ ?>
<a href="q/fundamentals/" class="btn">Start here</a>
<?php } else { ?>
<a href="signup/" class="btn">Start here</a>
<?php } ?>

<h2><?php echo get_theme_mod('ethics_tagline', ''); ?></h2>

<div id="topics">
	<div class="topic">
		<a href="<?=site_url()?>/about/">
			<div class="topic-icon"><i class="demo-icon icon-check"></i></div>
			<div class="topic-title">About This Project</div>
			<div class="topic-desc">Learn more about how this effort can support your newsroom.</div>
		</a>
	</div>
	<div class="topic">
		<a href="<?=site_url()?>/instructions/">
			<div class="topic-icon"><i class="demo-icon icon-mic-outline"></i></div>
			<div class="topic-title">Instructions</div>
			<div class="topic-desc">Everything you need to know to get started.</div>
		</a>
	</div>
	<div class="topic">
		<a href="<?=site_url()?>/faq/">
			<div class="topic-icon"><i class="demo-icon icon-quote-left"></i></div>
			<div class="topic-title">FAQ</div>
			<div class="topic-desc">Frequently asked questions and their answers.</div>
		</a>
	</div>
</div>

<div id="pages">
	<h3>Topics Covered in the Project</h3>
	<ul>
	  <?php $args = array("include" => array(272,199,202,283,217), "title_li" => null);
	  		wp_list_pages( $args ); ?>
	  		<li class="page_item"><a href="<?php echo site_url();?>/topics/">And dozens more</a></li>
	</ul>
	<?php get_search_form(); ?>
	<h2>The ONA ethics code was recently launched and we're inviting feedback, both on the language and on how the site performs. <a style="color: #54ACC9; text-decoration:none;" href="<?php echo get_site_url();?>/contact/">Please contact us here!</a></h2>
</div>

<?php get_footer(); ?>
