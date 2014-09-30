<?php
/**
 * @package no-login-redirect
 * @version 1.0
**/
/*
Plugin Name:no-login-redirect
Plugin URI:
Description:非ログイン時、ログイン画面にリダイレクトさせる
Author YAT,Chiaki
Version:1.0
*/
function nlr_theme_name_script(){
	wp_enqueue_style( 'nologinredirect' , plugins_url('nologin-redirect-style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts' , 'nlr_theme_name_script');

function nlr_no_login_redirect($content){
	if( !is_user_logged_in() ){
		$url = ( empty( $_SERVER["HTTPS"] ) ? 'http://' : 'https://' ) . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		wp_safe_redirect( wp_login_url( $url ) );
		exit;
	}
}

add_action('wp_head', 'nlr_no_login_redirect');
?>