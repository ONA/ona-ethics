<?php
// Redirect user if not logged in
if ( !is_user_logged_in() ){
	wp_redirect( home_url().'/signin/' );
	exit;
}

?>

<!DOCTYPE html>

<html <?php language_attributes(); ?>>

	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >

		<meta property="og:title" content="ONA15 | Online News Association">
		<meta property="og:type" content="website">
		<meta property="og:url" content="http://ona15.journalists.org">
		<meta property="og:image" content="http://ona15.journalists.org/wp-content/themes/ONA15/images/facebook.jpg">
		<meta property="og:site_name" content="ONA15 | Online News Association">
		<meta property="og:description" content="ONA15 is the premier gathering of highly engaged digital journalists who are shaping the future of media.">


		<title><?php wp_title('|', true, 'right'); ?></title>

		<?php wp_head();
			$steps = $wp->steps;
			$categories = $wp->categories; ?>

	</head>

	<body <?php body_class(); ?>>
		<div class="print">
		<?php
		$fundamentals = '
		<h3>Telling the truth</h3>
		<li>Be honest, accurate, truthful and fair. Do not distort or fabricate facts, imagery, sound or data.</li>
		<li>Provide accurate context for all reporting.</li>
		<li>Seek out diverse voices that can contribute important perspectives on the subject you’re writing.</li>
		<li>Ensure that sources are reliable. To the maximum extent possible, make clear to your audience who and what your sources are, what motivations your sources may have and any conditions people have set for giving you information. When unsure of information, leave it out or make clear it has not been corroborated.</li>
		<li>Correct errors quickly, completely and visibly. Make it easy for your audience to bring errors to your attention.</li>
		<li>If a report includes criticism of people or organizations, give them the opportunity to respond.</li>
		<li>Clearly distinguish fact from opinion in all content.</li>

		<h3>Conflicts of interest</h3>
		<li>Avoid any conflict of interest that undermines your ability to report fairly. Disclose to  your audience any unavoidable conflicts or other situational factors that may validly affect their judgment of your credibility.</li>
		<li>Do not allow people to make you dishonestly skew your reporting. Do not offer to skew your reporting under any circumstances.</li>
		<li>Do not allow the interests of advertisers or others funding your work to affect the integrity of your journalism.</li>

		<h3>Community</h3>
		<li>Respect your audience and those you write about. Consider how your work and its permanence may affect the subjects of your reporting, your community and ­­ since the Internet knows no boundaries ­­ the larger world.</li>

		<h3>Professional Conduct</h3>
		<li>Don’t plagiarize or violate copyrights.</li>
		<li>Keep promises to sources, readers and the community.</li>
		<li>If you belong to a news organization, give all staff expectations, support and tools to maintain ethical standards.</li>';


		foreach ($steps as $key => $val){

			if ( $val['name'] == "Fundamentals") continue;

			$question_data = get_user_meta( get_current_user_id(), 'question'.$key, true);

			if ( $val['level'] == 1 && ($categories[$val['parent']]['title']
				// Or if this is the nature of your journalism
				|| $val['name']=='Ethical Choices' ) ){

				$next = getNextEthic($key);
				$next_data = get_user_meta( get_current_user_id(), 'question'.$next, true);
				if ( empty($next_data) ) continue;
				if ($notfirst) {
					echo '</ul>';
				} else {
					$notfirst = true;
				}
				echo '<h2>'.($val['name']=='Ethical Choices' ? 'Nature of Your Journalism' : $val['name']).'</h2>';
				echo '<ul>';
			}

			if ($question_data[0] != 'na' && $question_data != 'na' ) {
				if ( $question_data[0] == 'skipped' || $question_data == 'skipped' ) {
					//echo '<p>You skipped over the introduction to this section. Please <a href="'.get_permalink($key).'">read through the overview</a>.</p>';
					continue;
				}
				if ( is_array($question_data) ){
					foreach ($question_data as $ethic){
						if ($ethic != '')
							echo '<li class="editable" data-href="'.get_permalink($key).'">'.$ethic.'</li>';
					}
				}
			}
		}

		echo '</ul>'; ?>

			<hr/>
			<p><i>Prepared using the Online News Association's Build-Your-Own Ethics Code project.</i></p>
			<p><i>Create your own at <?=site_url()?></i></p>
			<div class="print logo"></div>

		</div>

		<?php wp_footer(); ?>

	</body>
</html>
