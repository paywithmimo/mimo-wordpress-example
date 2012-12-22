<?php
/*
 Template Name: Create Article
*/

get_header();
/* get looged in user info*/
global $current_user;	
		get_currentuserinfo();
		
		
		$article_tile='';
		$article_summary='';
		$article_content='';
		$keywords='';
		$error=array();
		
		//server side validation
		if(isset($_POST['submit']))
		{
			$category = $_POST['category'];
			
			$article_tile = trim($_POST['ArticleTitle']);
			
			$article_summary = trim($_POST['ArticleSummary']);
			
			$article_content = trim($_POST['ArticleContent']);
			
			$keywords = trim($_POST['Keywords']);
			
			if($category=='0')
			{
				$error["cat"]="<div style='color:red'>Please select category</div><br/>";
			}
			if($article_tile =='')
			{
				$error['title']="<div style='color:red'>Please enter article title</div><br/>";
			}
			if($article_summary =='')
			{
				$error['summary']= "<div style='color:red'>Please enter article summary</div><br/>";
			}
			if($article_content =='')
			{
				$error['content']="<div style='color:red'>Please enter article content</div><br/>";
			}
			if($keywords=='')
			{
				$error['keywords']="<div style='color:red'>Please enter keywords</div><br/>";
			}
			if($_POST['code']=='')
			{
				$error['code']="<div style='color:red'>Please enter code</div><br/>";
			}
			if($_POST['code']!='')
			{
				if($_POST['code']!=2)
				{
					$error['code']="<div style='color:red'>Please enter valid code</div><br/>";
				}
			}
			
			if(!isset($_POST['condition']))
			{
				$error['condition']="<div style='color:red'>Please accept Terms and Conditions</div><br/>";
			}
			

			//display errors
			if(isset($error["cat"]))
			{
				echo $error['cat'];
			}
			if(isset($error["title"]))
			{
				echo $error['title'];
			}
			if(isset($error["summary"]))
			{
				echo $error['summary'];
			}
			if(isset($error["content"]))
			{
				echo $error['content'];
			}
			if(isset($error["keywords"]))
			{
				echo $error['keywords'];
			}
			if(isset($error["code"]))
			{
				echo $error['code'];
			}
			if(isset($error['condition']))
			{
				echo $error['condition'];
			}
			
			if(!$error)
			{
				
				// Create post object
				$my_post = array(
						'post_title'    => $_POST['ArticleTitle'],
						'post_excerpt'  => $_POST['ArticleSummary'],
						'post_content'  => $_POST['ArticleContent'],
						'tax_input'      => array( 'post_tag' => array( $_POST['Keywords']) ),						
						'post_category' => array($_POST['category']),
						'post_status'   => 'pending',
						'post_author'   => $current_user->ID,
						'post_type' =>'articles'						
				);			
				// Insert the post into the database
				$post_ID = wp_insert_post( $my_post );
				if($post_ID)
				{
					echo "Article has been submitted successfully<br/>";
				}
				$_POST = array();
			}				
			
		}
		
//Checked the user is logged in or not

if ( is_user_logged_in() && $current_user->user_status==1 ) {
	
?>	
	<form action=<?php echo $_SERVER['REQUEST_URI']?> method="post">
<!-- 	Category: -->
	
		 <table width="1000px" align="center" cellpadding="0" cellspacing="0">

            <tr>
                <td width="100%" colspan="2">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="100%" align="left" valign="top">
                                <div style="height: 100%; width: 100%;">
                                    
 <script type="text/javascript" language="javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/addarticle.js"></script>

                                
   

    <table cellspacing="0" cellpadding="0" width="100%">

                                                                        <tr>
                                                                            <td>
                                                                                <table cellspacing="6" cellpadding="0">
                                                                                    <tr>
                                                                                        <td colspan="2" align="center">
                                                                                           
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                        </td>
                                                                                        <td class="msg">
                                                                                            All fields marked <b>*</b> are mandatory</td>
                                                                                   </tr>
                                                                                    <tr>
                                                                                        <td class="labeltext">
                                                                                            Category :
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <select name="category" id="ctl00_CPH_Articles_drpArticleCategory" tabindex="3" class="input_textarea" style="height:20px;width:302px;">
		<option value="0">-- Select Article Category --</option>
			<?php 		
			$category_ids = get_all_category_ids();
			foreach($category_ids as $cat_id) {
		  		$cat_name = get_cat_name($cat_id);
		  		?>
		  		<option value="<?php echo $cat_id ?>"><?php echo $cat_name;?></option>
		  		<?php 
				}		?>
		
	</select><span class="msg">*</span><span id="ctl00_CPH_Articles_vlddrpCategoryCmp" style="color:Red;display:none;"></span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="labeltext">
                                                                                            Article Title :
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <input name="ArticleTitle" type="text" maxlength="100" id="ctl00_CPH_Articles_txtArticleTitle" tabindex="4" value="<?php if(isset($_POST['ArticleTitle'])) echo $_POST['ArticleTitle'];?>" class="input_textarea" onkeyup="decreaseLength(this,this.form.txtTitleRemainingLength,101)" /><span
                                                                                                    class="msg">*</span>
                                                                                            <span id="ctl00_CPH_Articles_vldTitleReq" style="color:Red;display:none;"></span>
                                                                                            
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                        </td>
                                                                                        <td>
                                                                                            <p>
                                                                                                You have
                                                                                                <input type="text" name="txtTitleRemainingLength" value="100" readonly="readonly"
                                                                                                    style="height: 15px; font-size: 10px; font-weight:bold; border: 0px; width: 30px; padding-bottom: 2px;
                                                                                                    padding-left: 2px; color: #FE4902;" />characters left. No HTML allowed.</p>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="labeltext">
                                                                                            Summary :
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <textarea name="ArticleSummary" rows="4" cols="20" id="ctl00_CPH_Articles_txtArticleSummary" tabindex="5"  class="multilinetext" onKeyUp="decreaseLength(this,this.form.txtSummaryRemainingLength,301)" onKeyDown="decreaseLength(this,this.form.txtSummaryRemainingLength,301)"><?php if(isset($_POST['ArticleSummary'])) echo $_POST['ArticleSummary'];?></textarea><span
                                                                                                    class="msg">*</span>
                                                                                            <span id="ctl00_CPH_Articles_vldSummaryReq" style="color:Red;display:none;"></span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                        </td>
                                                                                        <td>
                                                                                            <p>
                                                                                                You have
                                                                                                <input type="text" name="txtSummaryRemainingLength" value="300" readonly="readonly"
                                                                                                    style="height: 15px; font-size: 10px; font-weight:bold; border: 0px; width: 30px; padding-bottom: 2px;
                                                                                                    padding-left: 2px; color: #FE4902;" />characters left. No HTML allowed.</p>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="labeltext">
                                                                                            Content :
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <textarea name="ArticleContent" rows="20" cols="20" id="ctl00_CPH_Articles_txtArticleContent" tabindex="6" class="multilinetext" onkeydown="CountWordsLeft(ctl00_CPH_Articles_txtArticleContent, contentcount,1000);" onkeyup="CountWordsLeft(ctl00_CPH_Articles_txtArticleContent, contentcount,1000);" onpaste="CountWordsLeft(ctl00_CPH_Articles_txtArticleContent, contentcount,250);"><?php if(isset($_POST['ArticleContent'])) echo $_POST['ArticleContent'];?></textarea><span
                                                                                                    class="msg">*</span>
                                                                                            <span id="ctl00_CPH_Articles_vldContentReq" style="color:Red;display:none;"></span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                        </td>
                                                                                        <td>
                                                                                            <p>
                                                                                               <!-- Minimum 250 words required. --> You have  <input readonly="readonly" name="contentcount" type="text" size="5" value="1000" style="height: 15px;
                                                                                                    font-size: 10px; font-weight:bold; border: 0px; width: 30px; padding-bottom: 2px; padding-left: 2px;
                                                                                                    color: #FE4902;" /> words left. No HTML allowed.</p>
                                                                                        </td>
                                                                                    </tr>
                                                                                    
                                                                                    <tr>
                                                                                        <td class="labeltext">
                                                                                            Keywords :
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <textarea name="Keywords" rows="2" cols="20" id="ctl00_CPH_Articles_txtKeywords" tabindex="8" class="multilinetext" onkeyup="decreaseLength(this,this.form.txtKeywordsRemainingLength,81)"><?php if(isset($_POST['Keywords'])) echo $_POST['Keywords'];?></textarea><span
                                                                                                    class="msg">*</span><span id="ctl00_CPH_Articles_vldKeywordsReq" style="color:Red;display:none;"></span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="labeltext">
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <p>
                                                                                                You have
                                                                                                <input type="text" name="txtKeywordsRemainingLength" value="80" readonly="readonly"
                                                                                                    style="height: 15px; font-size: 10px; font-weight:bold; border: 0px; width: 30px; padding-bottom: 2px;
                                                                                                    padding-left: 2px; color: #FE4902;" />characters left. No HTML allowed. Keywords
                                                                                                are saperated by comma(,).</p>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <div class='input_textarea' style='background-color:White;height:50px;width:170px;'>1+1</div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="labeltext">
                                                                                            Enter Code :
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <input name="code" type="text" maxlength="5" id="ctl00_CPH_Articles_txtCaptchaCode" tabindex="9" value="<?php if(isset($_POST['code'])) echo $_POST['code'];?>" class="input_textarea" style="width:100px;" />
                                                                                            <span id="ctl00_CPH_Articles_vldCodeReq" style="color:Red;display:none;"></span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                        </td>
                                                                                        <td align="left">
                                                                                            <span class="labeltext"><input id="ctl00_CPH_Articles_cbAcceptTerms" type="checkbox" name="condition" tabindex="10" /><label for="ctl00_CPH_Articles_cbAcceptTerms">I have read and accept the </label></span>
                                                                                            &nbsp;<a href="/terms-and-condition" id="ctl00_CPH_Articles_hypTerms" class="labeltext" target="_blank" style="font-size: 10px;">terms and conditions.</a>
                                                                                            <span id="ctl00_CPH_Articles_vldAcceptPoliciesCus" style="color:Red;display:none;"></span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="2" height="8px">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="submit" name="submit" value="Submit Article" onclick="javascript:rangevalidation(); return validate();WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl00$CPH_Articles$btnSubmitArticle&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, false))" id="ctl00_CPH_Articles_btnSubmitArticle" tabindex="11" title="Submit Article" class="button" />&nbsp;
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                               </table>
	</form>
<?php 	
} else {
	echo 'Please Login First ';
	echo '<br/><br/>To Login Click <a href="/?page_id=84">here</a> ';
	
}
//get_sidebar();



get_footer();
?>