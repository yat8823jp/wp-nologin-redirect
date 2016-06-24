<?php
/*
Plugin Name: No Login Redirect
Plugin URI:
Description: 非ログイン時、ログイン画面にリダイレクトさせる
Author: YAT, mel_cha
Version: 3.2
Text Domain: wp-nologin-redirect
*/
function nlr_theme_name_script() {
	wp_enqueue_style( 'wp-nologin-redirect', plugins_url( 'css/nologin-redirect-style.css', __FILE__ ), array(), null );
	wp_print_styles();
}//nlr_theme_name_script
add_action( 'login_enqueue_scripts', 'nlr_theme_name_script' );

function nlr_no_login_redirect( $content ) {
	global $pagenow;
	if( !is_user_logged_in() && !is_admin() && ( $pagenow != 'wp-login.php' ) && php_sapi_name() !== 'cli' ){
		auth_redirect();
	}
}//nlr_no_login_redirect
add_action( 'init', 'nlr_no_login_redirect' );

function nlr_plugins_loaded() {
	load_plugin_textdomain( 'wp-nologin-redirect', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}//nlr_plugins_loaded
add_action( 'plugins_loaded', 'nlr_plugins_loaded' );

//add menu
function nlr_add_menu() {
	add_options_page(
		'no-login-redirect',
		'no-login-redirect',
		'activate_plugins',
		'nlr',
		'nlr_options'
	);
}//nlr_add_menu
add_action( 'admin_menu', 'nlr_add_menu' );

function nlr_options( $message ) {
	if (! current_user_can( 'activate_plugins' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ), 'wp-nologin-redirect' );
	}
?>

	<div class="wrap">
	<h2><?php echo __( 'wp-nologin-redirect menu' , 'wp-nologin-redirect' ); ?></h2>

	<p><?php echo __( 'Type the message that you want to display the login screen.' , 'wp-nologin-redirect' ) ?></p>
	<p><?php echo __( 'If there is no input " Welcome to this site. Please log in to continue " it will be the standard' , 'wp-nologin-redirect' ) ?></p>
	<form action="" id="nlr-menu-form" method="post">
		<?php wp_nonce_field( 'nlr-nonce-key', 'nlr-menu' ); ?>
		<?php
			if( esc_textarea( get_option( 'nlrdata' ) ) ) {
				$message = get_option( 'nlrdata' );
			} else {
				$message = __( 'Welcome to this site. Please log in to continue', 'wp-nologin-redirect' );
			}
		?>
		<textarea name="nlrdata" id="nlrdata" cols="80" rows="10"><?php echo esc_textarea( $message ); ?></textarea>
		<p><input type="submit" value="<?php echo esc_attr( __('Save', 'wp-nologin-redirect' ) ); ?>" class="button button-primary button-large"></p>
	</form>
	</div>
<?php
	return $message;
}//nlr_options

function nlr_add_login_message() {
	if(! get_option( 'nlrdata' ) ) {
			$message = __( 'Welcome to this site. Please log in to continue', 'wp-nologin-redirect' );
	} else {
		$message = get_option( 'nlrdata' );
	}

	if ( empty( $message) ){
		return '<p class="login-attention">'. $message .'</p>';
	} else {
		return $message;
	}
}//nlr_add_login_message
add_filter( 'login_message', 'nlr_add_login_message' );

function nlr_init() {
	if( isset( $_POST['nlr-menu'] ) && $_POST['nlr-menu'] ) {
		if( check_admin_referer( 'nlr-nonce-key', 'nlr-menu' ) ) {
			$e = new WP_Error();

			if ( isset( $_POST['nlrdata'] ) && $_POST['nlrdata'] ) {
				update_option( 'nlrdata', $_POST['nlrdata'] );
				$e->add(
					'error',
					__( 'saved the message', 'wp-nologin-redirect' )
				);
				set_transient( 'nlr-admin-errors', $e->get_error_messages(), 10 );
			} else {
				update_option( 'nlrdata', '' );
			}//if

			wp_safe_redirect( menu_page_url( 'nlr-menu', false ) );
		}//if
	}//if
}//nlr_init
add_action( 'admin_init', 'nlr_init' );

function nlr_admin_notices() {
	if( $messages = get_transient( 'nlr-admin-errors' ) ) {
?>
	<div class="updated">
		<ul>
	<?php foreach( $messages as $message ) { ?>
			<li><?php echo esc_html($message); ?></li>
	<?php }//foreach ?>
		</ul>
	</div>
<?php
	}//if
}//nlr_admin_notices
add_action( 'admin_notices', 'nlr_admin_notices' );
?>
