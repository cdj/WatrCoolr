$(document).ready(function(){
	$("#FeatureVideo").bind('timeupdate', function() {
		var state = "";
		if ($("#FeatureVideo").prop("ended"))
		{
			state = "ended";
		}
		else if ($("#FeatureVideo").prop("paused"))
		{
			state = "paused";
		}
		else
		{
			state = "playing";
		}
		
		$.ajax({
		  type: "POST",
		  url: "updatePlayTime.php",
		  data: { "MediaID" : MediaID, "CurrentTime" : $("#FeatureVideo").prop("currentTime"), "State" : state },
		  // callback handler that will be called on success
		  success: function(response, textStatus, jqXHR){
			// log a message to the console
			console.log("Time updated");
		  },
		  // callback handler that will be called on error
		  error: function(jqXHR, textStatus, errorThrown){
			// log the error to the console
			console.log(
				"The following error occured: "+
				textStatus, errorThrown
			);
		  }
		 });
	});
});