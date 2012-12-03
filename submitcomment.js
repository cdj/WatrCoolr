$(document).ready(function(){
	$("#form1").submit(function(event) {
		var $form = $(this);
			// let's select and cache all the fields
		var	$inputs = $form.find("input, select, button, textarea");
			// serialize the data in the form
		var	serializedData = $form.serialize();
	
		// let's disable the inputs for the duration of the ajax request
		$inputs.attr("disabled", "disabled");
		
		$.ajax({
		  type: "POST",
		  url: "addComment.php",
		  data: serializedData,
		  // callback handler that will be called on success
		  success: function(response, textStatus, jqXHR){
			// log a message to the console
			console.log("Comment submitted successfully");
			$("input#CommentText").val('');
		  },
		  // callback handler that will be called on error
		  error: function(jqXHR, textStatus, errorThrown){
			// log the error to the console
			console.log(
				"The following error occured: "+
				textStatus, errorThrown
			);
		  },
		  // callback handler that will be called on completion
		  // which means, either on success or error
		  complete: function(){
			// enable the inputs
			$inputs.removeAttr("disabled");
			$("input#CommentText").focus();
		  }
		 });
		
		// prevent default posting of form
		//event.preventDefault();
		return false;
	});
});