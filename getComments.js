// JavaScript Document
$(document).ready(function(){
	var shownComments = {};
	var userID = getCookie("UserID");
	var commentsUL = $('#commentsList');
	var intComments = setInterval(function(){
		if(mediaID >= 0) {
			$.get("getComments.php", { MediaID : mediaID }, function(data){
				var len = data.length;
				var toDel = [];

				//here we remove
				for(shownId in shownComments) {
					if(!shownIn(shownId, data)) {
						var elem = $('#comment-'+shownId);
						elem.fadeOut(400,function(){elem.remove()});
						toDel.push(shownId);
					}
				}
				for(i = 0; i < toDel.length; i++) {
					delete shownComments[toDel[i]];
				}

				//here we add
				for(i = 0; i < len; i++){
					var row = data[i];
					if(shownComments[row['CommentID']] === undefined){
						shownComments[row['CommentID']] = row;
						var classOwner = row['UserID'] === userID ? 'comment-owner': '';
						var htmlItem = $('<li class="comment hidden '+classOwner+'" id="comment-'+row['CommentID']+'">'+
							row['UserName']+'<br/>'+row['Comment']+'</li>');
						commentsUL.append(htmlItem);
						htmlItem.fadeIn();
					}
				}
			}, 'json')
			.error(function(data) { console.log("Error " + data.status + ": " + data.responseText); });
		}
	}, 500);

	function shownIn(elem, data) {
		var len = data.length;
		for(i = 0; i < len; i++) {
			if(data[i]['CommentID'] === elem) return true;
		}
		return false;
	}
 });