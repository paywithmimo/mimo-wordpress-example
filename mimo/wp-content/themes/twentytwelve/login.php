<?php
/*
Template Name: Custom WordPress Login
*/

//get_header();
?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/style.css" type="text/css">
<?php 
global $current_user;
	get_currentuserinfo();

if($_POST){

	//We shall SQL escape all inputs
	
// 	$username = $wpdb->escape($_REQUEST['username']);
// 	$password = $wpdb->escape($_REQUEST['password']);
// 	$remember = $wpdb->escape($_REQUEST['rememberme']);

	$username = $wpdb->escape($_POST['username']);
	$password = $wpdb->escape($_POST['password']);
	$remember = $wpdb->escape($_POST['rememberme']);
	
	if($remember) $remember = "true";
	else $remember = "false";
	$login_data = array();
	$login_data['user_login'] = $username;
	$login_data['user_password'] = $password;
	$login_data['remember'] = $remember;
	
	$user_verify = wp_signon( $login_data, true );
	
	if ( is_wp_error($user_verify))
	{
		$err_auth_msg = "<span class='error' style='color:red'>Invalid username or password. Please try again!</span>";		
	}
	
	if ( !is_wp_error($user_verify))
	{
		
		$querystr =  "SELECT user_status from $wpdb->users where user_login='$username'";
		
		$user_data = $wpdb->get_results($querystr, OBJECT);
		
		$user_status = $user_data['0']->user_status;
		
		if($user_status!=1)
		{
			$err_auth_msg =  "<span class='error' style='color:blue'>Sorry!! For the inconvenience. Your account is not active this time.</span>";			
		}
		else {
			wp_redirect('/?page_id=4');
			exit;
		}
	}
	
}


if ( !is_user_logged_in()) {
?>
<div id="login" style="margin: 50px 0px 0px 500px;">
		<h1><a href="<?php echo $_SERVER['REQUEST_URI'];?>" title="Powered by WordPress">eZine</a></h1>
		<br/>
		<h1><a href="/" title="Powered by WordPress">Login</a></h1><br/>
		<?php if(isset($err_auth_msg)) echo $err_auth_msg; ?>
	
<form name="loginform" id="loginform" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
	<p>
		<label for="user_login">Username<br />
		<input type="text" name="username" id="user_login" class="input" value="" size="20" /></label>
	</p>
	<p>
		<label for="user_pass">Password<br />
		<input type="password" name="password" id="user_pass" class="input" value="" size="20" /></label>
	</p>
	<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever"  /> Remember Me</label></p>
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Log In" />
		<input type="hidden" name="redirect_to" value="http://192.168.101.106:85/wp-admin/" />
		<input type="hidden" name="testcookie" value="1" />
	</p>
</form>
<a href="/?page_id=92">Register</a><br/><br/>
<?php
} 
else
{
	wp_redirect('/?page_id=4');
	exit();
}
	get_footer(); 
?>