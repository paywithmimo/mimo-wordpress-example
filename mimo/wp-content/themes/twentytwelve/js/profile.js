// JScript File

var sErr = "";

function rangevalidation()
    {
	    if (document.getElementById('user_pass').value =="")
        {
            sErr += "Please enter password. \n";
        }
        
       if (document.getElementById('user_pass').value !="")        	
        {
        	if (document.getElementById('user_pass').value.length < 6)
        		{
        			sErr += "Password must be greater then 6 characters. \n";
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
