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
function theme_name_script(){
	wp_enqueue_style( 'nologinredirect' , plugins_url('nologin-redirect-style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts' , 'theme_name_script');

function no_login_redirect($content){
	global $post;
	$id = $post->ID;
	$status = $post -> post_status;
	if( is_user_logged_in() ){
		//
		return ' <span class="f12 red">[' . $id . ']</span>' . $content;
	}
	return $content;
}

add_filter('the_content','no_login_redirect');
?>