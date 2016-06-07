<?php
// Redirect user if not logged in
if ( !is_user_logged_in() ){
	wp_redirect( home_url().'/signin/' );
	exit;
}

$sections = '';

get_header();
$steps = $wp->steps;
$categories = $wp->categories; ?>

<div class="main-column" role="content">

	<h1><?=_('Your Ethics Code');?></h1>

	<p><a href="print/" target="_blank"><span class="demo-icon icon-print"></span> Print</a> <!--| <span class="demo-icon icon-mail"></span> Email--></p>

	<div class="code yourcode">

		<h1>Journalism Fundamentals</h1>

		<h2>Telling the truth</h2>
		<ul>
		<li>Be honest, accurate, truthful and fair. Do not distort or fabricate facts, imagery, sound or data.</li>
		<li>Provide accurate context for all reporting.</li>
		<li>Seek out diverse voices that can contribute important perspectives on the subject you’re writing.</li>
		<li>Ensure that sources are reliable. To the maximum extent possible, make clear to your audience who and what your sources are, what motivations your sources may have and any conditions people have set for giving you information. When unsure of information, leave it out or make clear it has not been corroborated.</li>
		<li>Correct errors quickly, completely and visibly. Make it easy for your audience to bring errors to your attention.</li>
		<li>If a report includes criticism of people or organizations, give them the opportunity to respond.</li>
		<li>Clearly distinguish fact from opinion in all content.</li>
		</ul>

		<h2>Conflicts of interest</h2>
		<ul>
		<li>Avoid any conflict of interest that undermines your ability to report fairly. Disclose to  your audience any unavoidable conflicts or other situational factors that may validly affect their judgment of your credibility.</li>
		<li>Do not allow people to make you dishonestly skew your reporting. Do not offer to skew your reporting under any circumstances.</li>
		<li>Do not allow the interests of advertisers or others funding your work to affect the integrity of your journalism.</li>
		</ul>

		<h2>Community</h2>
		<ul>
		<li>Respect your audience and those you write about. Consider how your work and its permanence may affect the subjects of your reporting, your community and ­­ since the Internet knows no boundaries ­­ the larger world.</li>
		</ul>

		<h2>Professional Conduct</h2>
		<ul>
		<li>Don’t plagiarize or violate copyrights.</li>
		<li>Keep promises to sources, readers and the community.</li>
		<li>If you belong to a news organization, give all staff expectations, support and tools to maintain ethical standards.</li>
		</ul>
	</div>

	<div class="code yourcode">
		<?php
		foreach ($steps as $key => $val){

			if ( $val['name'] == "Fundamentals") continue;

			$question_data = get_user_meta( get_current_user_id(), 'question'.$key, true);

			if ( $val['level'] == 1 && ($categories[$val['parent']]['title']
				// Or if this is the nature of your journalism
				|| $val['name']=='Ethical Choices' ) ){

				// If both of the next questions are empty or skipped
				$next = getNextEthic($key);
				$next_data = get_user_meta( get_current_user_id(), 'question'.$next, true);
				$next_next = getNextEthic($next);
				$next_next_data = get_user_meta( get_current_user_id(), 'question'.$next_next, true);

				if ( empty($next_data) || $next_data[0] == 'skipped' || $next_data[0] == 'na') {
					if ($next_next_data['level'] == 1 || !is_array($next_next_data)){
						continue;
					} else if (empty($next_next_data) || $next_next_data[0] == 'skipped' || $next_next_data[0] == 'na') {
						continue;
					}
				}

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

		echo '</ul>';
		// This <a> button moves around with jQuery ?>
		<a href="" id="buttons"><i class="demo-icon icon-pencil"></i></a>
	</div>
</div>

<?php get_footer(); ?>
