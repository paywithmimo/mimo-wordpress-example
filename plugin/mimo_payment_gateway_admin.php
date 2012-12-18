<?php 
	if($_POST['mimo_hidden'] == 'Y') { 
		$mi_oauth_url_mt = esc_url($_POST['mi_oauth_url_mt']);
		update_option('mi_oauth_url_mt', $mi_oauth_url_mt);		
		$mi_oauth_url = esc_url($_POST['mi_oauth_url']);
		update_option('mi_oauth_url', $mi_oauth_url);
		$mi_client_id = $_POST['mi_client_id'];
		update_option('mi_client_id', $mi_client_id);
		$mi_client_secret = $_POST['mi_client_secret'];
		update_option('mi_client_secret', $mi_client_secret);
	?>
	<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
     <?php
	} else {
		//Normal page display
		$mi_oauth_url_mt = get_option('mi_oauth_url_mt');
		$mi_oauth_url = get_option('mi_oauth_url');
		$mi_client_id = get_option('mi_client_id');
		$mi_client_secret = get_option('mi_client_secret');		
	}	

?>

<div class="wrap">
<?php    echo "<center><u><h2>" . __( 'How to use On Site:') . "</h2></u></center>";
	echo "<p class='info'>Description</p>";
?>
<form name="mimo_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" class="mimo_fm">
<input type="hidden" name="mimo_hidden" value="Y">

<?php    echo "<h4>" . __( 'Mimo Settings', 'mimo_dom' ) . "</h4>"; ?>
<p><span class="ttl01"><?php _e("OAuth Callback URL for MoneyTransfer : " ); ?></span>
<input type="text" name="mi_oauth_url_mt" value="<?php echo $mi_oauth_url_mt; ?>" size="20"></p>
<p><span class="ttl01"><?php _e("OAuth Callback URL for UserSearch : " ); ?></span>
<input type="text" name="mi_oauth_url" value="<?php echo $mi_oauth_url; ?>" size="20"></p>
<p><span class="ttl01"><?php _e("Client Id : " ); ?></span>
<input type="text" name="mi_client_id" value="<?php echo $mi_client_id; ?>" size="20"></p>
<p><span class="ttl01"><?php _e("Client Secret : " ); ?></span>
<input type="text" name="mi_client_secret" value="<?php echo $mi_client_secret; ?>" size="20"></p>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save', 'mimo_dom' ) ?>" />
</p>
</form>
</div>
