<?php

class RatePlugin {
	static $instance;

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	protected function __construct() {
		add_action( 'template_redirect', array( $this, 'assets' ) );
		add_action( 'wp_ajax_nopriv_rate_item', array( $this, 'ajax_rate_item' ) );
		add_action( 'wp_ajax_rate_item', array( $this, 'ajax_rate_item' ) );
		add_action( 'comment_post', array( $this, 'comment_post' ) );
		add_action( 'comment_form_top', array( $this, 'comment_form_top' ) );
	}

	public function comment_post( $id ) {
		global $wpdb;

		if ( ! isset( $_POST['comment_karma'] ) ) {
			return;
		}

		$wpdb->update(
			$wpdb->comments,
			array( 'comment_karma' => (int) $_POST['comment_karma'] ),
			array( 'comment_ID' => $id )
		);
	}

	public function comment_form_top() {
		$star = '<li class="empty"><span class="l"></span><span class="r"></span></li>';
		printf(
			'<ul class="rating form-rating">%s</ul>',
			str_repeat( $star, 5 )
		);
	}

	public function ajax_rate_item() {
		global $wpdb;

		$comment_ID = (int) $_POST['comment_ID'];
		$comment_post_ID = (int) $_POST['comment_post_ID'];
		$comment_karma = (int) $_POST['rating'];

		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			$user->display_name = $user->user_login;
		}

		if ( is_user_logged_in() ) {
			$comment_author = esc_sql( $user->display_name );
		} elseif ( isset( $_COOKIE['comment_author_' . COOKIEHASH] ) ) {
			$comment_author = $_COOKIE['comment_author_' . COOKIEHASH];
		} else {
			$comment_author = '';
		}

		if ( is_user_logged_in() ) {
			$comment_author_email = esc_sql( $user->user_email );
		} elseif ( isset( $_COOKIE['comment_author_email_' . COOKIEHASH] ) ) {
			$comment_author_email = $_COOKIE['comment_author_email_' . COOKIEHASH];
		} else {
			$comment_author_email = '';
		}

		if ( is_user_logged_in() ) {
			$comment_author_url = esc_sql( $user->user_url );
		} elseif ( isset( $_COOKIE['comment_author_url_' . COOKIEHASH] ) ) {
			$comment_author_url = $_COOKIE['comment_author_url_' . COOKIEHASH];
		} else {
			$comment_author_url = '';
		}

		if ( empty( $comment_author ) || empty( $comment_author_email ) ) {
			die('I don\'t know who you are!');
		}

		$comment_approved = 1;

		$commentdata = compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_karma', 'comment_approved' );

		if ( $comment_ID > 0 ) {
			$response = $wpdb->update( $wpdb->comments, $commentdata, array( 'comment_ID' => $comment_ID ) );
		} else {
			$response = $wpdb->insert( $wpdb->comments, $commentdata );
		}

		echo $response;
		exit();
	}

	public function assets() {
		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );
		add_action( 'wp_print_styles', array( $this, 'wp_print_styles' ) );
	}

	public function wp_print_styles() {
		if ( is_file( STYLESHEETPATH . '/rate.css' ) ) {
			wp_enqueue_style( 'user-rate', get_bloginfo( 'stylesheet_directory') . '/rate.css' );
		} elseif ( is_file( TEMPLATEPATH . '/rate.css' ) ) {
			wp_enqueue_style( 'user-rate', get_bloginfo( 'template_directory' ) . '/rate.css' );
		} else {
			wp_enqueue_style( 'rate', plugins_url( 'css/rate.css', dirname( __FILE__ ) ) );
		}
	}

	public function wp_print_scripts() {
		wp_enqueue_script( 'rate', plugins_url( 'js/rate.js', dirname( __FILE__ ) ), array( 'jquery' ), '0.4', true );
	}
}