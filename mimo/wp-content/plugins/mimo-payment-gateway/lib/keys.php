<?php   
// Access token parameters
$apiKey=get_option('mi_client_id');
$apiSecret=get_option('mi_client_secret');
if((get_option('mi_oauth_url')))
	$redirectUri = get_option('mi_oauth_url');
else
	$redirectUri =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if((get_option('mi_oauth_url_mt')))
	$oauth_uri_mt = get_option('mi_oauth_url_mt');
else
	$oauth_uri_mt =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if((get_option('mi_oauth_url_rf')))
	$oauth_uri_rf = get_option('mi_oauth_url_rf');
else
	$oauth_uri_rf =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if((get_option('mi_oauth_url_void')))
	$redirectUri_void = get_option('mi_oauth_url_void');
else
	$redirectUri_void =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(isset($_SESSION['token']))
	$token=$_SESSION['token'];
else
	$token = '';

?>
