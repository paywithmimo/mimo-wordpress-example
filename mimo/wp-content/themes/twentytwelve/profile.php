<?php

/*Template Name: Profile
 */
get_header();
?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/style.css" type="text/css">
<script type="text/javascript" language="javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/profile.js"></script>
<?php 
global $curren_user;
		get_currentuserinfo();
		
		
$user_id = $current_user->ID;
$username='';
$email='';
$pass='';
$confirm_pass='';
$fname='';
$lname='';
$error=array();

//server side validation
if(isset($_POST['wp-submit']))
{
		
	$pass = trim($_POST['user_pass']);	
		
	
// 	if($email =='')
// 	{
// 		$error['email']="<div style='color:red'>Please enter email</div><br/>";
// 	}
// 	if($email!='')
// 	{
// 		if(eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$',$email))
// 		{
// 			if ( email_exists($email) )
// 				$error['email_exist']="<div style='color:red'>Email already exist</div><br/>";
// 		}
// 		else
// 		{
// 			$error["email"]="<div style='color:red'>Please Enter Valid Email Address</div>";
// 		}
// 	}
	
	if($pass =='')
	{
		$error['pass']="<div style='color:red'>Please enter password</div><br/>";
	}	
	
	if(isset($error["pass"]))
	{
		echo $error['pass'];
	}
		
	if(!$error)
	{
		
		$fname = $_POST['user_fname'];
		$lname = $_POST['user_lname'];
		$about_me = $_POST['about_me'];
		$website = $_POST['user_url'];
		$password = $_POST['user_pass'];
		$user_update = wp_update_user( array ('ID' => $user_id, 'user_url' => $website,'first_name'=>$fname,'last_name'=>$lname,'description'=>$about_me,'user_pass'=>$password));
				
		if($user_update)
		{
			echo "Profile Updated";			 
		}
		$_POST = array();
	}
}
?>
<?php


$user_meta = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = '{$user_id}' ",OBJECT);

$user_details = $wpdb->get_results("SELECT * FROM {$wpdb->users} WHERE ID = '{$user_id}' ",OBJECT);

 $first_name_update = $user_meta['0']->meta_value;
 $last_name_update  = $user_meta['1']->meta_value;
 $about_me_update   = $user_meta['3']->meta_value;
 
 
 $username_update   = $user_details ['0']->user_login;
 $user_email_update = $user_details['0']->user_email;
 $user_url_update   = $user_details['0']->user_url;
 
 
 if(is_user_logged_in()){
 ?>

	<div id="login">
	<h1><a href="<?php echo $_SERVER['REQUEST_URI']?>" title="Powered by WordPress">Profile</a></h1><br/>
		 <span class="msg">All fields marked <b>*</b> are mandatory</span>
<form name="registerform" id="registerform" action=<?php echo $_SERVER['REQUEST_URI'];?> method="post">

<h3>Name</h3>
	<p>
		<label for="user_login">Username<br />
		<input type="text" name="user_login" id="user_login" class="input" value="<?php if(isset($_POST['user_login'])) echo $_POST['user_login'];else  if(isset($username_update)) echo $username_update;?>" size="25" readonly /><span class="msg">Username can not be changed</span></label>
	</p><br/>	
	<p>
		<label for="user_login">Role</label>
		<?php 
	
?>
	<select name="user_role" id="user_role"  class="input" disabled>
		<?php
				//get current user role
				$user = new WP_User( $user_id );
				$current_user_role =  $user->roles[0];
		
			$user = get_userdata( $current_user->ID );
			$selected='';
	$capabilities = $user->{$wpdb->prefix . 'capabilities'};
	
	if ( !isset( $wp_roles ) )
		$wp_roles = new WP_Roles();

	foreach ( $wp_roles->role_names as $role => $name ) :
	
		if ( array_key_exists( $role, $capabilities ) )
	{
		if($role == $current_user_role)
			$selected = "selected=selected";
		?>
			<option value="<?php echo $role ?>" <?php echo $selected;?>><?php echo $role;?></option>
			
<?php }
	endforeach;?>		  		
	</p>	
	</select><span class="msg">*</span><span id="ctl00_CPH_Articles_vlddrpCategoryCmp" style="color:Red;display:none;"></span>
	
	<p>
		<label for="user_fname">First Name<br />
		<input type="text" name="user_fname" id="user_fname" class="input" value="<?php if(isset($_POST['user_fname'])) echo $_POST['user_fname'];else  if(isset($first_name_update)) echo $first_name_update;?>" size="25" /></label>
	</p>
	<p>
		<label for="user_lname">Last Name<br />
		<input type="text" name="user_lname" id="user_lname" class="input" value="<?php if(isset($_POST['user_lname'])) echo $_POST['user_lname'];else  if(isset($last_name_update)) echo $last_name_update;?>" size="25" /></label>
	</p>
<h3>Contact Info</h3>	
	<p>
		<label for="user_email">E-mail<br />
		<input type="text" name="user_email" id="user_email" class="input" value="<?php if(isset($_POST['user_email'])) echo $_POST['user_email'];else  if(isset($user_email_update)) echo $user_email_update;?>" size="25" readonly /><span class="msg">Email can not be changed</span></label>
	</p>
	<p>
		<label for="url">Website<br />
		<input type="text" name="user_url" id="user_url" class="input" value="<?php if(isset($_POST['user_email'])) echo $_POST['user_email'];else  if(isset($user_url_update)) echo $user_url_update;?>" size="25" /><span class="msg"></span></label>
	</p>
<h3>About User</h3>	
	<p>
		<label for="about_me">Biographical Info<br />
		<textarea type="text" name="about_me" id="about_me" class="input"  rows="5" cols="40"><?php if(isset($_POST['about_me'])) echo $_POST['about_me'];else  if(isset($about_me_update)) echo $about_me_update;?></textarea></label>
	</p>	
	<p>
		<label for="user_pass">Password<br />
		<input type="password" name="user_pass" id="user_pass" class="input" value="<?php if(isset($_POST['user_pass'])) echo $_POST['user_pass'];else  if(isset($first_name_update)) echo $first_name_update;?>" size="25" /><span class="msg">*</span></label>
	</p>
<!-- 	<p> -->
<!-- 		<label for="user_confirm_pass">Confirm Password<br /> -->
		<!-- <input type="password" name="user_confirm_pass" id="user_confirm_pass" class="input" value="<?php if(isset($_POST['user_confirm_pass'])) echo $_POST['user_confirm_pass'];?>" size="25" /><span class="msg">*</span></label>
<!-- 	</p>	 -->

	
	<!-- <p id="reg_passmail">A password will be e-mailed to you.</p>-->
	<br class="clear" />
	<input type="hidden" name="redirect_to" value="" />
	<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" onclick="javascript:rangevalidation(); return validate();" value="Register" /></p>
	
</form>
<?php
 }
	else 
	{
		wp_redirect('/?page_id=84');
		exit();
	} 
	
get_footer();
?>