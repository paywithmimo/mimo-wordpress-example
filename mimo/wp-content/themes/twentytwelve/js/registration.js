// JScript File

var sErr = "";

function rangevalidation()
    {
	       
        if (document.getElementById('user_login').value =="")
        {
            sErr += "Please enter username. \n";            
        }        
        
        if (document.getElementById('user_email').value =="")
        {
            sErr += "Please enter email. \n";
        }
      
        if(document.getElementById('user_email').value != "")
        {
            var emailPat1 = /^(\".*\"|[a-zA-Z0-9]+\.[a-zA-Z0-9]\w*)@(\[\d{1,3}(\.\d{1,3}){3}]|[A-Za-z]\w*(\.[A-Za-z]\w*)+)$/;
            var emailPat = /^(\".*\"|[A-Za-z]\w*)@(\[\d{1,3}(\.\d{1,3}){3}]|[A-Za-z]\w*(\.[A-Za-z]\w*)+)$/;
            
            var emailid=document.getElementById('user_email').value;
            
            var matchArray = emailid.match(emailPat);
            var matchArray1 = emailid.match(emailPat1);
            if (matchArray == null && matchArray1 == null)
            {
                sErr += "Your email seems incorrect. \n";
            }
        }
        
        if (document.getElementById('user_pass').value =="")
        {
            sErr += "Please enter password. \n";
        }
        
        if (document.getElementById('user_confirm_pass').value =="")
        {
            sErr += "Please enter confirm password. \n";
        }
        if (document.getElementById('user_pass').value !="")        	
        {
        	if (document.getElementById('user_pass').value.length < 6)
        		{
        			sErr += "Password must be greater then 6 characters. \n";
        		}
        }
        
        if (document.getElementById('user_pass').value !="" && document.getElementById('user_confirm_pass').value !="")
        {
        	if(document.getElementById('user_pass').value != document.getElementById('user_confirm_pass').value)
        		{
        			sErr += "Password and confirm password must be same. \n";
        		}
        }
        
        return sErr;
        
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
