$(document).ready(function(){
	$("#UserRegistrationForm").submit(function(event) {
		var $form = $(this);
			// let's select and cache all the fields
		var	$inputs = $form.find("input, select, button, textarea");
			// serialize the data in the form
		var	serializedData = $form.serialize();
	
		// let's disable the inputs for the duration of the ajax request
		$inputs.attr("disabled", "disabled");
		
		$.ajax({
		  type: "POST",
		  url: "registerUser.php",
		  data: serializedData,
		  // callback handler that will be called on success
		  success: function(response, textStatus, jqXHR){
			// log a message to the console
			console.log("User registered successfully. ID is " + response);
			setCookie("UserID", response);
			$('input#UserID').val(response);
			startConversation();
		  },
		  // callback handler that will be called on error
		  error: function(jqXHR, textStatus, errorThrown){
			// log the error to the console
			console.log(
				"The following error occured: "+
				textStatus, errorThrown
			);
			$inputs.removeAttr("disabled");
			$("input#UserName").focus();
		  }
		 });
		
		// prevent default posting of form
		return false;
	});
});
function getCookie(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
	  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
	  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
	  x=x.replace(/^\s+|\s+$/g,"");
	  if (x==c_name)
	  {
		  return unescape(y);
	  }
	}
}
function setCookie(c_name, value)
{
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + 365);
	var c_value = escape(value) + "; expires=" + exdate.toUTCString();
	document.cookie = c_name + "=" + c_value;
}