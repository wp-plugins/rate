<?php
/**
 * @global stdClass $current_user
 * @return string
 */
function rate_current_user() {
	global $current_user;
	if ( is_user_logged_in() ) {
		get_currentuserinfo();
		return $current_user->user_login;
	} else {
		return $_COOKIE['comment_author_' . COOKIEHASH];
	}
}

/**
 * @return bool
 */
function rate_user_is_known() {
	return is_user_logged_in() || ! empty( $_COOKIE['comment_author_' . COOKIEHASH] );
}

function rate_calculate( $id = 0, $is_comment = false ) {
	global $wpdb;

	$coerced_id = (int) $id > 0 ? $id : get_the_ID();
	$previous_id = 0;

	if ( $is_comment ) {
		$c = $GLOBALS['comment'];
		$rating = (int) $c->comment_karma;
		$previous_id = (int) $c->comment_ID;
	} else {
		$sql = $wpdb->prepare(
			"SELECT AVG(comment_karma) FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_karma > 0 AND comment_approved = 1",
			$coerced_id
		);
		$rating = $wpdb->get_var( $sql );
	}
	$rating = (float) number_format( $rating, 1, '.', '' );

	if ( $rating === 0.0 ) {
		$coerced_rating = 0.0;
	} elseif ( ( $rating * 10 ) % 5 !== 0 ) {
		$coerced_rating = round( $rating * 2.0, 0 ) / 2.0;
	} else {
		$coerced_rating = $rating;
	}

	$stars = array( 0, 1, 2, 3, 4, 5, 6 );
	$classes = array( 'rating' );
	$format = '<li class="%s"><span class="l"></span><span class="r"></span></li>';

	for ( $i = 1; $i <= 5; $i++ ) {
		if ( $i <= $coerced_rating ) {
			$stars[$i] = sprintf( $format, 'whole' );
		} elseif ( $i - 0.5 === $coerced_rating ) {
			$stars[$i] = sprintf( $format, 'half' );
		} else {
			$stars[$i] = sprintf( $format, 'empty' );
		}
	}

	$usermeta = array();
	if ( rate_user_is_known() ) {
		if ( $is_comment && ( (int) $rating === 0 || $c->comment_author === rate_current_user() ) ) {
			$classes[] = 'needs-rating';
		}
	   	$usermeta[] = sprintf( 'data-id="%d"', $coerced_id );
	   	if ( $previous_id > 0 ) {
	   		$usermeta[] = sprintf( 'data-comment-id="%d"', $previous_id );
	   	}
	}

	$stars[0] = sprintf(
		'<ul data-rating="%01.1f" class="%s" %s>',
		$rating,
		join( ' ', $classes ),
		join( ' ', $usermeta )
	);
	$stars[6] = '</ul>';

	return implode('', $stars);
}

function the_rating( $id = 0 ) {
	echo rate_calculate( $id );
}

function the_comment_rating() {
	global $comment;
	echo rate_calculate( $comment->comment_post_ID, true );
}