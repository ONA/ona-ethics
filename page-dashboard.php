<?php
// Redirect user if not logged in
if ( !is_user_logged_in() ){
	wp_redirect( home_url().'/signin/' );
	exit;
}

$sections = '';
$first_name = get_user_meta(get_current_user_id(), 'first_name', true);

get_header();
global $wp;
$steps = $wp->steps;
$categories = $wp->categories;
//print_r($wp->categories); ?>

<div id="question" class="main-column float">
	<h1><?php _e('Your Dashboard');?></h1>
	<?php
	$all_parents = -1; // Compensate for the Nature of your journalism child
	$all_answered = 0;
	/*echo '<pre>';
	print_r($steps);
	echo '</pre>';*/
	foreach ($steps as $key => $val){
		$steps[$key]['values'] = get_user_meta( get_current_user_id(), 'question'.$key, true);
		$answered = true;
		$skipped = ($steps[$key]['values'][0] == 'skipped' ? true : false);

		if ( empty($steps[$key]['values']) ) {
			$answered = false;
			if (!$next) {
				// if this is not a parent, find the next parent; we then link to that
				if ( !$steps[$key]['parent'] ) continue;
				$next = $steps[$key];
				$next['link'] = get_permalink($key);
			}
		}

		// Is this a top-level category?
		if ( $val['first'] && $categories[$val['parent']]['title']){
			if ( $started ){
				// This is a new parent, so we print the previous parent
				$sections .= '<div class="section">';
				$sections .= '<h4><a href="'.$lastLink.'">'.$lastName.'</a> ('.$answered_total.'/'.$steps_total.')</h4>';
				$sections .= '<div class="segments">';
				$percent = (($answered_total/$steps_total)*100);
				$sections .= '<div class="segment'.($percent==100?' answered':'').'" style="width:'.$percent.'%"></div>';
				$sections .= '</div>';
				$sections .= '</div>';
				$answered_total = 0;
				$steps_total = 0;
			} else {
				// This is the first parent
				$answered_total = 0;
				$steps_total = 0;
				$started = true;
			}
			$lastLink = get_permalink($key);
			$lastName = $categories[$val['parent']]['title'];
		}

		if ( $val['parent'] && $val['level'] == 1 ) {
			if ($answered){ // There is data for this question
				$answered_total++; // Just for this category; reset later
				$all_answered++;  // For __ of __
			}
			$steps_total++; // Just for this category; reset later
			$all_parents++; // For __ of __
		}
	}
	// Echo the last one, because it won't be triggered above
	$sections .= '<div class="section">';
	$sections .= '<h4><a href="'.$lastLink.'">'.$lastName.'</a> ('.$answered_total.'/'.$steps_total.')</h4>';
	$sections .= '<div class="segments">';
	$percent = (($answered_total/$steps_total)*100);
	$sections .= '<div class="segment'.($percent==100?' answered':'').'" style="width:'.$percent.'%"></div>';
	$sections .= '</div>';
	$sections .= '</div>';


	if ($all_answered == 0) { ?>
		<p><?php printf( __( 'Hello%s. You have completed <b>%d</b> of <b>%d</b> sections. Start building your code using the sections below.'), ($first_name ? ', '.$first_name : ''), $all_answered, $all_parents);?></p>
	<?php } else { ?>
		<p><?php printf( __( 'Hello%s. You have completed <b>%d</b> of <b>%d</b> sections. Continue building your code using the sections below.'), ($first_name ? ', '.$first_name : ''), $all_answered, $all_parents);?></p>
	<?php } ?>
	<?php if ($next) {
		echo '<a class="btn dashboard" href="'.$next['link'].'"><b>NEXT:</b> '.$next['name'].'</a>';
	} ?>
	<div class="sections">
		<?php echo $sections; ?>
	</div>


</div>

<?php get_sidebar('question'); ?>
<?php get_footer(); ?>
