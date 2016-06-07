<?php

// Add meta boxes to post types


function question_form_meta_box() {

	$screens = array( 'question' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'question_form',
			__( 'Options for Form' ),
			'question_form_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'question_form_meta_box' );

// Prints the box content
function question_form_callback( $post ) {

	wp_nonce_field( 'question_form_meta_box', 'question_form_meta_box_nonce' );

	// Get value for each field
	// $question_field_type 	= get_post_meta( $post->ID, '_question_field_type', true );
	$question_options 		= get_post_meta( $post->ID, '_question_options', true );
	if (!$question_options){
		$question_options[] = get_post_meta( $post->ID, '_question_option_1', true );
		$question_options[] = get_post_meta( $post->ID, '_question_option_2', true );
		$question_options[] = get_post_meta( $post->ID, '_question_option_3', true );
		$question_options[] = get_post_meta( $post->ID, '_question_option_4', true );
		$question_options[] = get_post_meta( $post->ID, '_question_option_5', true );
	}

	$next 					= get_post_meta( $post->ID, '_next', true );
	$information 			= get_post_meta( $post->ID, '_information', true );

	echo '<label for="information">';
	_e( 'Is this really a question?' );
	echo '</label>';
	echo '<select name="information">';
	echo '<option value="1"'.selected($information, 1).'>Yes</option>';
	echo '<option value="0"'.selected($information, 0).'>Just information</option>';
	echo '</select>';

	echo '<p>These options will be displayed in a select field in the question.</p>';

	foreach ( $question_options as $k => $option ){
		echo '<textarea rows="4" id="question_option_'.$k.'" name="question_options[]" style="width: 100%;">' . esc_attr( $option ) . '</textarea><br/><br/>';
	} ?>

	<div id="addOption">Add Another Option</div>

	<div class="clone" style="display:none;">
		<textarea rows="4" id="question_option" name="" style="width: 100%;"></textarea><br/><br/>
	</div>

	<script>
	( function( $ ) {
		var i = 50;
		$('#addOption').on('click', function(){
			console.log('merp');
			$clone = $('.clone').clone().removeClass('clone');
			$clone.find('textarea').attr('id', 'question_option'+i).attr('name', 'question_options[]');
			$clone.insertBefore(this).show();
		});

	} )( jQuery );
	</script>

	<?php
	echo '<p>Enter the ID of the question that should be asked next:</p>';

	// The below field isn't needed anymore, but I'm just hiding it for now
	echo '<input type="hidden" id="next" name="next" value="' . esc_attr( $next ) . '" size="25" /><br/>';
}


// When the post is saved, saves our custom data.
function question_form_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['question_form_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['question_form_meta_box_nonce'], 'question_form_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Sanitize user input.
	foreach ( $_POST['question_options'] as $k => $option ){
		$option = sanitize_text_field( $option );
		if ($option == ''){
			unset($_POST['question_options'][$k]);
		}
	}

	$information = sanitize_text_field( $_POST['information'] );
	$next = sanitize_text_field( $_POST['next'] );

	// Update the meta field in the database.
	delete_post_meta( $post_id, '_question_option_1', $option1 );
	delete_post_meta( $post_id, '_question_option_2', $option2 );
	delete_post_meta( $post_id, '_question_option_3', $option3 );
	delete_post_meta( $post_id, '_question_option_4', $option4 );
	delete_post_meta( $post_id, '_question_option_5', $option5 );

	update_post_meta( $post_id, '_question_options', $_POST['question_options'] );
	update_post_meta( $post_id, '_information', $information );
	update_post_meta( $post_id, '_next', $next );
}
add_action( 'save_post', 'question_form_save_meta_box_data' );


?>


