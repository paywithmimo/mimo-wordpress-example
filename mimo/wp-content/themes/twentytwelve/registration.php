<?php
/*Template Name: Registration
 */
?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/style.css" type="text/css">
<script type="text/javascript" language="javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/registration.js"></script>
<?php 

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
	$username = trim($_POST['user_login']);
	
	$email = trim($_POST['user_email']);

	$pass = trim($_POST['user_pass']);
	
	$confirm_pass = trim($_POST['user_confirm_pass']);
				
	if($username=='')
	{
		$error["username"]="<div style='color:red'>Please enter username</div><br/>";
	}
	if($username!='')
	{
		if ( username_exists( $username ) )
				$error['user_exist']="<div style='color:red'>Username already exist</div><br/>";
	}
	
	if($email =='')
	{
		$error['email']="<div style='color:red'>Please enter email</div><br/>";
	}
	if($email!='')
	{
		if(eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$',$email))
		{
			if ( email_exists($email) )
				$error['email_exist']="<div style='color:red'>Email already exist</div><br/>";
		}
		else
		{
			$error["email"]="<div style='color:red'>Please Enter Valid Email Address</div>";
		}
	}
	
	if($pass =='')
	{
		$error['pass']="<div style='color:red'>Please enter password</div><br/>";
	}
	
	if($confirm_pass =='')
	{
		$error['confirm_pass']="<div style='color:red'>Please enter confirmpass</div><br/>";
	}
	
	if($pass!='' && $confirm_pass!='')
	{
		if($pass!=$confirm_pass)
		{
			$error['pass']="<div style='color:red'>Password and confirm password must be same</div><br/>";
		}
	}
	if(isset($error["username"]))
	{
		echo $error['username'];
	}
	if(isset($error["email"]))
	{
		echo $error['email'];
	}
	if(isset($error["pass"]))
	{
		echo $error['pass'];
	}
	if(isset($error["confirm_pass"]))
	{
		echo $error['confirm_pass'];
	}
	if(isset($error["user_exist"]))
	{
		echo $error['user_exist'];
	}
	
	if(isset($error["email_exist"]))
	{
		echo $error['email_exist'];
	}
	if(!$error)
	{
		
		$un = $_POST['user_login'];
		$user_email = $_POST['user_email'];
		$fname = $_POST['user_fname'];
		$lname = $_POST['user_lname'];
		
		$user_ID = wp_insert_user( array ('user_login'=>$un,'user_pass'=>$_POST['user_pass'],'user_email'=>$user_email,'first_name'=>$fname,'last_name'=>$lname,'role'=>'author'));
		
		if($user_ID)
		{
			echo "Thank you for registration";
			$to = $_POST['user_email'];
			$subject = 'eZine Registration';
			$message  = 'Thank you for registration \n ';
			$message  = 'Your credential are as follow \n ';			
			$message .='Username:'.$_POST['user_login'].'\n';
			$message .='Password:'.$_POST['user_pass'].'\n';
			
			if(wp_mail( $to, $subject, $message))
			{
				wp_redirect('/?page_id=84');				
			} 
		}
		$_POST = array();
	}
}
?>

	<div id="login">
	<h1><a href="<?php echo $_SERVER['REQUEST_URI']?>" title="Powered by WordPress">Registration</a></h1><br/>
		 <span class="msg">All fields marked <b>*</b> are mandatory</span>
<form name="registerform" id="registerform" action=<?php echo $_SERVER['REQUEST_URI'];?> method="post">

	<p>
		<label for="user_login">Username<br />
		<input type="text" name="user_login" id="user_login" class="input" value="<?php if(isset($_POST['user_login'])) echo $_POST['user_login'];?>" size="25" /><span class="msg">*</span></label>
	</p>
	<p>
		<label for="user_email">E-mail<br />
		<input type="text" name="user_email" id="user_email" class="input" value="<?php if(isset($_POST['user_email'])) echo $_POST['user_email'];?>" size="25" /><span class="msg">*</span></label>
	</p>
	<p>
		<label for="user_pass">Password<br />
		<input type="password" name="user_pass" id="user_pass" class="input" value="<?php if(isset($_POST['user_pass'])) echo $_POST['user_pass'];?>" size="25" /><span class="msg">*</span></label>
	</p>
	<p>
		<label for="user_confirm_pass">Confirm Password<br />
		<input type="password" name="user_confirm_pass" id="user_confirm_pass" class="input" value="<?php if(isset($_POST['user_confirm_pass'])) echo $_POST['user_confirm_pass'];?>" size="25" /><span class="msg">*</span></label>
	</p>
	<p>
		<label for="user_fname">First Name<br />
		<input type="text" name="user_fname" id="user_fname" class="input" value="<?php if(isset($_POST['user_fname'])) echo $_POST['user_fname'];?>" size="25" /></label>
	</p>
	<p>
		<label for="user_lname">Last Name<br />
		<input type="text" name="user_lname" id="user_lname" class="input" value="<?php if(isset($_POST['user_lname'])) echo $_POST['user_lname'];?>" size="25" /></label>
	</p>	
	
	<!-- <p id="reg_passmail">A password will be e-mailed to you.</p>-->
	<br class="clear" />
	<input type="hidden" name="redirect_to" value="" />
	<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" onclick="javascript:rangevalidation(); return validate();" value="Register" /></p>
	
</form>
<a href="/?page_id=84">Login</a><br/><br/>

<?php 
	
get_footer();
?>