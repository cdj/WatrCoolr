// JavaScript Document
var shownComments = {};
var gotComments;
function logLast(){
	console.log(gotComments);
}
$(document).ready(function(){
	var commentsUL = $('#commentsList');
	var now = new Date();
	var utcString = now.getUTCFullYear().toString()
		+ makeString(now.getUTCMonth() + 1, 2)
		+ makeString(now.getUTCDate(), 2)
		+ makeString(now.getUTCHours(), 2)
		+ makeString(now.getUTCMinutes(), 2)
		+ makeString(now.getUTCSeconds(), 2);
	console.log(utcString);
	var intComments = setInterval(function(){
		if(mediaID >= 0) {
			var userID = getCookie("UserID");
			if (userID == null || userID == "")
			{
				window.location.reload();
			}
			$.get("getComments.php", { MediaID : mediaID }, function(data){
				gotComments = data;
				var len = data.length;
				var toDel = [];

				//here we remove
				for(shownId in shownComments) {
					if(!shownIn(shownId, data)) {
						toDel.push(shownId);
					}
				}
				for(i = 0; i < toDel.length; i++) {
					var shownId = toDel[i];
					var elem = $('#comment-'+shownId);
					elem.fadeOut(400,function(){elem.remove();delete shownComments[shownId];});
				}

				//here we add
				for(i = 0; i < len; i++){
					var row = data[i];
					if(shownComments[row['CommentID']] === undefined){
						console.log(row['CommentTime']);
						shownComments[row['CommentID']] = row;
						var classOwner = row['UserID'] === userID ? 'alert-success': 'alert-info';
						var liOther = row['UserID'] === userID ? '': 'comment-other';
						var htmlItem = $('<li class="comment transparent '+liOther+'" id="comment-'+row['CommentID']+
							'"><div class="alert '+classOwner+'"><span class="comment-name">'+
							row['UserName']+'</span><br/>'+row['Comment']+'</div></li>');
						commentsUL.append(htmlItem);
						htmlItem.animate(
							{opacity: row['CommentTime'] > utcString ? 1 : 0.5},
							400
						);
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

	function makeString(num, len) {
		var numStr = num.toString();
		while(numStr.length < len) {
			numStr = 0 + numStr;
		}
		return numStr;
	}
 });