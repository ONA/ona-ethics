<?php 
// Redirect user if not logged in
if ( !is_user_logged_in() ){
	wp_redirect( home_url().'/signin/' );
	exit;
}

get_header();

$steps = $wp->steps; ?>

<div id="question" class="main-column float">
	<?php if (have_posts()) : while (have_posts()) : the_post();
		$id = get_the_ID();
		$options = get_post_meta( $id, '_question_options', true );
		if (!$options) {
			$options[] = get_post_meta( $id, '_question_option_1', true );
			$options[] = get_post_meta( $id, '_question_option_2', true );
			$options[] = get_post_meta( $id, '_question_option_3', true );
			$options[] = get_post_meta( $id, '_question_option_4', true );
			$options[] = get_post_meta( $id, '_question_option_5', true );
		}
		$information = get_post_meta( $id, '_information', true );

		$keys = array_keys($steps);
		$current = array_search($id, $keys);
		$next = $keys[$current+1];

		if ( is_numeric($next) ) {
			$target = get_permalink( $next );
		} else {
			$end = true;
			$target = site_url().'/your-ethics-code/';
		}

		$question_data = get_user_meta( get_current_user_id(), 'question'.$id, true);
		?>

	<h1><?=get_the_title();?></h1>

	<?php if ( $steps[get_the_ID()]['parent'] == true && get_the_ID() != 5) {
		$goto = -1;
		foreach ($steps as $k => $v) {
		   if ( $nextparent && $v['parent'] ) {
		   	$goto = $k;
		   	break;
		   }
		   if ($k == get_the_ID() ) $nextparent = true;
		}
		if ( !get_post_status($goto) ) {
			$goto_link = site_url().'/your-ethics-code/';
			$goto_title = 'your Dashboard';
		} else {
			$goto_link = get_the_permalink($goto);
			$goto_title = '"'.get_the_title($goto).'"';
		} ?>

		<form class="ethics-question checkbox skip" method="post" action="<?=$goto_link?>">
			<input type="hidden" name="question_response[]" value="skipped" />
			<input type="hidden" name="qID" value="<?=$post->ID?>" />
			<input type="submit" class="skip" value='Skip and proceed to <?=$goto_title?> &rarr;' />
		</form>
	<?php } ?>

	<?php the_content(); ?>


	<form class="ethics-question checkbox" method="post" action="<?=$target?>">

		<?php if ($information){
			if ( !is_array($question_data) ){
				$answer = $question_data;
				$question_data = array();
				$question_data[0] = $answer;
			}
			// These label fields can either be checkboxes or radio buttons
			foreach ($options as $option){ ?>
				<label <?=(in_array($option, $question_data)?'class="selected"':'')?>><span class="demo-icon <?=(in_array($option, $question_data)?'icon-check-2':'icon-check-empty')?>"></span><input type="checkbox" name="question_response[]" <?=(in_array($option, $question_data)?'checked="checked"':'')?> value="<?=$option;?>"><?=$option;?></label>
				<?php if(($key = array_search($option, $question_data)) !== false) { unset($question_data[$key]); }
			}
			$question_data = array_values($question_data); ?>

			<p><b>OPTIONAL</b> &mdash; If the choices above do not cover your needs, you may add your own ethics statements here, separated by a line break. You may also copy a statement above and paste into the box to edit.</p>
			<textarea rows="5" name="question_response[]"><?php if (isset($question_data[0]) && $question_data[0] != 'skipped') { foreach($question_data as $q){ echo $q."\n"; } } ?></textarea>
		<?php } else { ?>
		<input type="hidden" name="question_response[]" value="na" />
		<?php } ?>
		<input type="hidden" name="qID" value="<?=$post->ID?>" />
		<input type="submit" class="btn" value="<?=($end?'Get your ethics code':($information?'Save and Continue':'Continue'));?>" />
	</form>

	<p><em>Do you have feedback about this ethical issue? <a href="/contact/">Submit your questions or thoughts</a> to our team.</em></p>

	<?php endwhile; endif ?>
</div>

<script>
var confirmOnPageExit = function (e) {
    // If we haven't been passed the event get the window.event
    e = e || window.event;
    var message = 'The changes you made will be lost if you navigate away from this page.';
    // For IE6-8 and Firefox prior to version 4
    if (e) {
        e.returnValue = message;
    }
    // For Chrome, Safari, IE8+ and Opera 12+
    return message;
};
/*
jQuery('input[type=checkbox]').on('click', function(){
	window.onbeforeunload = confirmOnPageExit;
});
jQuery('input[type=submit].btn').on('click', function(){
	window.onbeforeunload = null;
});
*/
</script>

<?php get_sidebar('question'); ?>

<?php get_footer(); ?>
