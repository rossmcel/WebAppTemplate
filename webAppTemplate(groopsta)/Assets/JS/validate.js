/* Javascript Form Validation */

function validate() {
			if(document.regForm.emailAddress.value == "" && document.regForm.password.value == "" && document.regForm.firstname.value == "")
			{
				document.getElementById("fnameMessage3").innerHTML = "Please enter your first name";
				document.getElementById("fnameMessage3").className = "missing";
				document.getElementById("fnameMessage").innerHTML = "Please enter your Email Address";
				document.getElementById("fnameMessage2").innerHTML = "Please enter a password";
				document.getElementById("fnameMessage").className = "missing";
				document.getElementById("fnameMessage2").className = "missing";

				return false;
			} 
			else if(document.regForm.emailAddress.value == "" && document.regForm.password.value == "")
			{
				document.getElementById("fnameMessage").innerHTML = "Please enter your Email Address";
				document.getElementById("fnameMessage2").innerHTML = "Please enter a password";
				document.getElementById("fnameMessage").className = "missing";
				document.getElementById("fnameMessage2").className = "missing";
				return false;
			} 
			else if(document.regForm.emailAddress.value == "" && document.regForm.firstname.value == "")
			{
				document.getElementById("fnameMessage").innerHTML = "Please enter your Email Address";
				document.getElementById("fnameMessage").className = "missing";
				document.getElementById("fnameMessage3").innerHTML = "Please enter your first name";
				document.getElementById("fnameMessage3").className = "missing";
				return false;
			} 
			else if(document.regForm.password.value == "" && document.regForm.firstname.value == "")
			{
				document.getElementById("fnameMessage2").innerHTML = "Please enter a password";
				document.getElementById("fnameMessage2").className = "missing";
				document.getElementById("fnameMessage3").innerHTML = "Please enter your first name";
				document.getElementById("fnameMessage3").className = "missing";
				return false;
			} 
			else if(document.regForm.emailAddress.value == "")
			{
				document.getElementById("fnameMessage").innerHTML = "Please enter your Email Address";
				document.getElementById("fnameMessage").className = "missing";
				return false;
			} 
			else if(document.regForm.password.value == "")
			{
				document.getElementById("fnameMessage2").innerHTML = "Please enter a password";
				document.getElementById("fnameMessage2").className = "missing";
				return false;
			} 
			else if(document.regForm.firstname.value == "")
			{
				document.getElementById("fnameMessage3").innerHTML = "Please enter your first name";
				document.getElementById("fnameMessage3").className = "missing";
				return false;
			}

			return(true);
		}
