// JScript File

var sErr = "";

function rangevalidation()
    {
        
        if (document.getElementById('ctl00_CPH_Articles_drpArticleCategory').options[document.getElementById('ctl00_CPH_Articles_drpArticleCategory').selectedIndex].value == 0)
        {
           sErr += "Please select Article Category. \n";        
           
        }        
        
        if (document.getElementById('ctl00_CPH_Articles_txtArticleTitle').value =="")
        {
            sErr += "Please enter Article Title. \n";            
        }
        /*if (document.getElementById('ctl00_CPH_Articles_txtArticleTitle').value !="")
        {
            if (document.getElementById('ctl00_CPH_Articles_txtArticleTitle').value.length < 10 || document.getElementById('ctl00_CPH_Articles_txtArticleTitle').value.length > 100 )
            {
                sErr += "Article title must be of 10-100 characters. \n";
            }
        }*/
        
        
        if (document.getElementById('ctl00_CPH_Articles_txtArticleSummary').value =="")
        {
            sErr += "Please enter Article Summary. \n";
        }
       /* if (document.getElementById('ctl00_CPH_Articles_txtArticleSummary').value !="")
        {
            if (document.getElementById('ctl00_CPH_Articles_txtArticleSummary').value.length > 300)
            {
                sErr += "Article summary must be less than 300 characters. \n";
            }
        }*/
        
        if (document.getElementById('ctl00_CPH_Articles_txtArticleContent').value =="")
        {
            sErr += "Please enter Article Content. \n";
        }
        if (document.getElementById('ctl00_CPH_Articles_txtArticleContent').value !="")
        {
            var articlecontent = document.getElementById('ctl00_CPH_Articles_txtArticleContent').value;
            var mySplitResult = articlecontent.split(" ");
            if (mySplitResult.length > 1000)
            {
               sErr += "Article content must be smaller than 1000 words. \n";
            }
        }
        
        if (document.getElementById('ctl00_CPH_Articles_txtKeywords').value =="")
        {
            sErr += "Please enter Keywords. \n";
        }
       /* if (document.getElementById('ctl00_CPH_Articles_txtKeywords').value !="")
        {
            if (document.getElementById('ctl00_CPH_Articles_txtKeywords').value.length > 80)
            {
                sErr += "Article keywords must be less than 80 characters. \n";
            }
        }*/
        
        
        if (document.getElementById('ctl00_CPH_Articles_txtCaptchaCode').value =="")
        {
            sErr += "Please enter the text shown in the image. \n";

        }
        if (document.getElementById('ctl00_CPH_Articles_txtCaptchaCode').value !="")        	
        {
        	if (document.getElementById('ctl00_CPH_Articles_txtCaptchaCode').value !="2")
        			sErr += "Please enter the correct code. \n";

        }
        
        if (document.getElementById('ctl00_CPH_Articles_cbAcceptTerms').checked == false)
        {
            sErr += 'Please accept the terms and conditions. \n';
        }
        
        return sErr;
        
    }

  function decreaseLength(obj,objTextLength, lnth)
  {	
	objTextLength.value= lnth - (obj.value.length + 1);
	if (obj.value.length > lnth) 
    {
	    obj.value = obj.value.substring(0, lnth - 1);
	}
  }
  
  function CountWordsLeft(field, count, min_words) 
  {
    var text=field.value + " ";
    
    if(min_words>0)
    {
        var iwhitespace = /^[^A-Za-z0-9]+/gi; // remove initial whitespace
        var left_trimmedStr = text.replace(iwhitespace, "");
        var na = rExp = /[^A-Za-z0-9]+/gi; // non alphanumeric characters
        var cleanedStr = left_trimmedStr.replace(na, " ");
        var splitString = cleanedStr.split(" ");
        var word_count = splitString.length -1;
        var toatl_words = min_words-word_count;
        
        if(toatl_words < 0)
        {
            count.value= min_words + (word_count - min_words);
        }
        else
       {
            count.value= toatl_words;
       }
    }
  }

    function validate()
    {
       if(sErr == "")
        {
            return true;
        }
        if(sErr != "")
        {
            alert(sErr);
            sErr = "";
            return false;
        }
    }
