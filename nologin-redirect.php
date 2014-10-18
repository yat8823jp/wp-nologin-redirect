<?php
/*
Plugin Name: No Login Redirect
Plugin URI:
Description:非ログイン時、ログイン画面にリダイレクトさせる
Author YAT,Chiaki
Version:2.1
*/
function nlr_theme_name_script() {
	wp_enqueue_style( 'nologin', plugins_url( 'nologin-redirect-style.css', __FILE__ ), array(), null );
	wp_print_styles();
}
add_action( 'login_enqueue_scripts', 'nlr_theme_name_script' );

function nlr_no_login_redirect( $content ) {
	global $pagenow;
	if( !is_user_logged_in() && !is_admin() && ( $pagenow != 'wp-login.php' ) ){
		$url = ( empty( $_SERVER["HTTPS"] ) ? 'http://' : 'https://' ) . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		wp_safe_redirect( wp_login_url( $url ) );
		exit;
	}
}
add_action( 'init', 'nlr_no_login_redirect' );

function nlr_add_login_message( $message ) {
	if ( empty($message) ){
		return '<p class="login-attention">'.__( 'Welcome to this site. Please log in to continue', 'no-login-redirect' ).'</p>';
	} else {
		return $message;
	}
}
add_filter( 'login_message', 'nlr_add_login_message' );


?>
