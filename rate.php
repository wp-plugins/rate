<?php 
/*
Plugin Name: Rate
Description: Ratings: clean, lightweight and easy
Author: Scott Taylor
Version: 0.3
Author URI: http://tsunamiorigami.com
*/

function rate_current_user() {
	global $current_user;
	if (is_user_logged_in()) {
		get_currentuserinfo();
		return $current_user->user_login;
	} else {
		return $_COOKIE['comment_author_' . COOKIEHASH];
	}
}

function rate_user_is_known() {
	return is_user_logged_in() || !empty($_COOKIE['comment_author_' . COOKIEHASH]);
}

function rate_calculate($id = 0, $is_comment = false) {
	global $wpdb;
	$url = get_permalink();
	$coerced_id = (int) $id > 0 ? $id : get_the_id();
	$previous_id = 0;
	
	if ($is_comment) {
		$c = $GLOBALS['comment'];	
		$rating = (int) $c->comment_karma;
		$previous_id = (int) $c->comment_ID;
	} else {
		$rating = $wpdb->get_var(
			$wpdb->prepare("SELECT AVG(comment_karma) FROM $wpdb->comments WHERE ". 
				"comment_post_ID = %d AND comment_karma > %d AND comment_approved = %d", 
				$coerced_id, 0, 1)); 		
	}
	$rating = (float) number_format($rating, 1, '.', '');
	
	if ($rating === 0.0) {
		$coerced_rating = 0.0;
	} else if (($rating * 10) % 5 !== 0) {
		$coerced_rating = round($rating * 2.0, 0) / 2.0;
	} else {
		$coerced_rating = $rating;
	}
	
	$stars = array(0,1,2,3,4,5,6);
	$classes = array('rating');
	$format = '<li class="%s"><span class="l"></span><span class="r"></span></li>';
	
	for ($i = 1; $i <= 5; $i++) {
		if ($i <= $coerced_rating) {
			$stars[$i] = sprintf($format, 'whole');
		} else if ($i - 0.5 === $coerced_rating) {
			$stars[$i] = sprintf($format, 'half');
		} else {
			$stars[$i] = sprintf($format, 'empty');		
		}
	}	
	
	$usermeta = array();	
	if (rate_user_is_known()) {
		if ($is_comment && ((int) $rating === 0 || ($c->comment_author === rate_current_user()))) {
			$classes[] = 'needs-rating';	
		}
	   	$usermeta[] = sprintf('data-id="%d"', $coerced_id);
	   	if ($previous_id > 0) {
	   		$usermeta[] = sprintf('data-comment-id="%d"', $previous_id);
	   	}
	}	
	
	$stars[0] = sprintf('<ul data-rating="%01.1f" class="%s" %s>', $rating, implode(' ', $classes), implode(' ', $usermeta));
	$stars[6] = '</ul>';
	
	return implode('', $stars);		
}

function rate_save_karma($id) {
	global $wpdb;
	
	if (isset($_POST['comment_karma'])) {
		$wpdb->update($wpdb->comments, 
			array('comment_karma' => (int) $_POST['comment_karma']),
			array('comment_ID' => $id)
		);
	}
}
add_action('comment_post', 'rate_save_karma');

function rate_form_filter($content) {
	$star = '<li class="empty"><span class="l"></span><span class="r"></span></li>';
	$parts = array('<ul class="rating form-rating">', $star, $star, $star, $star, $star, '</ul>');
	echo implode('', $parts);
}
add_action('comment_form_top', 'rate_form_filter');

function rate_item_callback() {
	global $wpdb;

	$comment_ID = (int) $_POST['comment_ID'];
	$comment_post_ID = (int) $_POST['comment_post_ID'];
	$comment_karma = (int) $_POST['rating'];

	if (is_user_logged_in()) {
		$user = wp_get_current_user();	
		$user->display_name = $user->user_login;
		
		$comment_author = $wpdb->escape($user->display_name);	
		$comment_author_email = $wpdb->escape($user->user_email);
		$comment_author_url   = $wpdb->escape($user->user_url);		
	} else {
		$comment_author = $wpdb->escape($_COOKIE['comment_author_' . COOKIEHASH]);	
		$comment_author_email = $wpdb->escape($_COOKIE['comment_author_email_' . COOKIEHASH]);
		$comment_author_url   = $wpdb->escape( $_COOKIE['comment_author_url_' . COOKIEHASH]);			
	}
	
	if (empty($comment_author) || empty($comment_author_email)) {
		die('I don\'t know who you are!');
	}

	$comment_approved = 1;
	
	$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_karma', 'comment_approved');

	if ($comment_ID > 0) {
		$response = $wpdb->update($wpdb->comments, $commentdata, array('comment_ID' => $comment_ID));
	} else {
		$response = $wpdb->insert($wpdb->comments, $commentdata);
	}	
	
	echo $response;
	exit();	
}
add_action('wp_ajax_nopriv_rate_item', 'rate_item_callback');
add_action('wp_ajax_rate_item', 'rate_item_callback');

function the_rating($id = 0) {
	echo rate_calculate($id);
}

function the_comment_rating() {
	$c = $GLOBALS['comment'];
	echo rate_calculate($c->comment_post_ID, true);
}

function rate_styles() {
	if (is_file(STYLESHEETPATH . '/rate.css')) {
		wp_enqueue_style('user-rate', get_bloginfo('stylesheet_directory') . '/rate.css');
	} else {
		wp_enqueue_style('rate', WP_PLUGIN_URL . '/rate/css/rate.css');	
	}
}
add_action('wp_print_styles', 'rate_styles');


function rate_scripts() {
	wp_enqueue_script('rate', WP_PLUGIN_URL . '/rate/js/rate.js', array('jquery'));
}
add_action('wp_print_scripts', 'rate_scripts');
?>