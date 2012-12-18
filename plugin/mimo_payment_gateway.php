<?php
/*
Plugin Name: Mimo Payment Gateway
Plugin URI: https://staging.mimo.com.ng
Description: This plugin for mimo payment gateway
Version: 1.0
Author:Mimo
License: GPL2
*/

/**
 * init_sessions()
 *
 * @uses session_id()
 * @uses session_start()
 */
function init_sessions() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'init_sessions');
//To add mimo css
wp_register_style('mimoStyle',plugins_url('css/style.css',__FILE__));
wp_enqueue_style('mimoStyle');
/**
 *  Search users shortcode :
 *
 * @param atts
 * @param content
 * @return Search results
 */
// Shortcode for user Search
function mimo_user_search($atts,$content=null)
{   
	
	//extract( shortcode_atts( array ( 'userfield' => '','datastring' => ''), $atts ) );
	require "lib/MimoRestClient.php";
	require "lib/keys.php";
	
	// Instantiate a new Mimo REST Client	
	$Mimo = new MimoRestClient($apiKey, $apiSecret, $redirectUri); 	
	if(!isset($_GET['code']) && !isset($_GET['error'])) { 
		//To fetch authurl and code
		$authUrl = $Mimo->getAuthUrl();  
		echo "<a href='$authUrl' class='auth_btn'>Get Oauth</a>";
	}
	//if error generated from Mimo
	if(isset($_GET['error'])) {
		echo "There was an error. Mimo said: {$_GET['error_description']}";
	}
	//To get code from Mimo
	else if(isset($_GET['code'])) {  
		$code = $_GET['code'];
		//Mimo token request
		$token = $Mimo->requestToken($code); 
		if(!$token) { $Mimo->getError(); } // Check for errors
		else {
			//To set token into session
			if(!isset($_SESSION))
			$_SESSION['token'] = $token;
		}
	// To get user account basic information
    $html='';
    $html .='<form name="userinfo_form" method="post" action="" >
    <table >
	<tbody><tr>
		<td><input id="rdblSearchParameter_0" type="radio" name="rdblSearchParameter" value="username" checked="checked"><label for="rdblSearchParameter_0">User Name</label></td>
	</tr><tr>
		<td><input id="rdblSearchParameter_1" type="radio" name="rdblSearchParameter" value="email"><label for="rdblSearchParameter_1">Email</label></td>
	</tr><tr>
		<td><input id="rdblSearchParameter_2" type="radio" name="rdblSearchParameter" value="phone"><label for="rdblSearchParameter_2">Phone</label></td>
	</tr><tr>
		<td><input id="rdblSearchParameter_3" type="radio" name="rdblSearchParameter" value="account_number"><label for="rdblSearchParameter_3">Account Number</label></td>
	</tr>
	</tbody></table>
    <br>
    Enter search value :<input name="txtValue" type="text" id="txtValue">
    <span id="VldValueReq" style="display:none;">Please enter serach parameter value</span>
    <br>
    <input type="submit" name="btnSubmit" value="Get User Profile"  id="btnSubmit"></form>';
    // Post data
   if(isset($_POST['btnSubmit'])){
		$userfield=$_POST['rdblSearchParameter'];
		$datastring=$_POST['txtValue'];
		// function call to get User information
		$user_info = $Mimo->getUser($userfield,$datastring);
	if(!$user_info) {
		$Mimo->getError(); 
	}else{
		 if(!empty($user_info['account_number'])){
					$html .= "<div class='display-table'>";
					$html .= "<span class='display-caption'>ID</span><span class='display-caption'>Image</span><span class='display-caption'>Account Number</span><span class='display-caption'>Account Type</span><span class='display-caption'>Company Name</span><span class='display-caption'>First Name</span><span class='display-caption'>Middle Name</span><span class='display-caption'>Surname</span><span class='display-caption'>Username</span><span class='display-caption'>Email</span><span class='display-caption'>Level</span> ";
					$html .= "<div class='display-row'>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['id'];
					$html .="</span>";
					$html .= "<span class='display-cell'><img src='";
					$html .=$user_info['photo_url'];
					$html .="' /></span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['account_number'];
					$html .="</span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['account_type'];
					$html .="</span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['company_name'];
					$html .="</span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['first_name'];
					$html .="</span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['middle_name'];
					$html .="</span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['surname'];
					$html .="</span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['username'];
					$html .="</span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['email'];
					$html .="</span>";
					$html .= "<span class='display-cell'>";
					$html .=$user_info['level'];
					$html .="</span>";
					$html .="</div>";
					$html .="</div>";
			}else{
				$html .= $user_info['Message'];
			}
		}	
   	  }
	}
   return $html;
}
add_shortcode('mimo-user','mimo_user_search');
/**
 *  To transfer the Money with the  authorized access token
 *
 * @param atts
 * @param content
 * @return Transaction information
 */
//Shortcode for Transaction
function mimo_money_transfer($atts,$content=null)
{ 
	require "lib/MimoRestClient.php";
	require "lib/keys.php";

	// Instantiate a new Mimo REST Client	
	$Mimo = new MimoRestClient($apiKey, $apiSecret, $oauth_uri); 	
	if(!isset($_GET['code']) && !isset($_GET['error'])) { 
		//To fetch authurl and code
		$authUrl = $Mimo->getAuthUrl();  
		echo "<a href='$authUrl' class='auth_btn'>Get Oauth</a>";
	}
	if(isset($_GET['error'])) {
		echo "There was an error. Mimo said: {$_GET['error_description']}";
	}
	else if(isset($_GET['code'])) {  
		$code = $_GET['code'];
		//Mimo token request
		$token = $Mimo->requestToken($code); 
		if(!$token) { $Mimo->getError(); } // Check for errors
		else {
			//To set token into session
			if(!isset($_SESSION))
			$_SESSION['token'] = $token;
		}	
	// To get user account basic information
    $html='';
    $html .='<form name="userinfo_form" method="post" action="" >
    <h1>Money Transfer</h1>
    <div class="main_div">
    	<div class="container_mt">	
    		<span class="ttl01"> Note :</span> <input name="txtnote" type="text" id="txtnote">
        </div>
       	<div class="container_mt">	
    		<span class="ttl01"> Amount : </span><input name="txtAmount" type="text" id="txtAmount">
    </div>
    <input type="submit" name="btnAmount" value="Money Transfer" id="btnAmount">
    	</div>	
    </form>';
   if(isset($_POST['btnAmount'])){ 
			$amount=$_POST['txtAmount'];
			$note=$_POST['txtnote'];
			// To get user account basic information
			$transaction = $Mimo->transaction($amount,$note);
	if(!$transaction) {
		$Mimo->getError(); 
	}else{
					if(empty($transaction['Success'])){
						$html .= $transaction['Message'];
					}else{
						$html .= $transaction['Success'];
					}
			}	
   	  }
	}
   return $html;			
}
add_shortcode('mimo-moneytransfer','mimo_money_transfer');
// set admin pages for Mimo
function mimo_payment_gateway_admin() {
	include('mimo_payment_gateway_admin.php');
}
//add mimo menu pages to admin menu
function mimo_payment_gateway_admin_actions() {
	add_options_page("Mimo Payment Gateway", "Mimo Payment Gateway", 'manage_options', "Mimo-Payment-Gateway", "mimo_payment_gateway_admin");
}
add_action('admin_menu', 'mimo_payment_gateway_admin_actions');
?>