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
	var addedAmount;
	var intComments = setInterval(function(){
		if(mediaID >= 0) {
		setListHeight();
		setBarWidth();
			var userID = getCookie("UserID");
			if (userID == null || userID == "")
			{
				return;
			}
			$.get("getComments.php", { MediaID : mediaID }, function(data){
				addedAmount = 0;
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
				
				// Is the list scrolled to the bottom?
				//  i.e. is the last comment on screen?
				var isAtBottom = commentsUL.children().length > 0 ? $(".comment").filter(":onScreen").last().is(":last-child") : true;

				//here we add
				for(i = 0; i < len; i++){
					var row = data[i];
					if(shownComments[row['CommentID']] === undefined){
						addedAmount++;
						shownComments[row['CommentID']] = row;
						var classOwner = row['UserID'] === userID ? 'alert-success': 'alert-info';
						var liOther = row['UserID'] === userID ? '': 'comment-other';
						var htmlItem = $('<li class="comment transparent '+liOther+'" id="comment-'+row['CommentID']+
							'"><div class="alert '+classOwner+' '+row['Mood']+'"><span class="comment-name">'+
							row['UserName']+'</span><br/>'+row['Comment']+'</div></li>');
						commentsUL.append(htmlItem);
						htmlItem.animate(
							{opacity: row['CommentTime'] > utcString ? 1 : 0.4},
							400
						);
					}
				}
				
				// If we were at the bottom before, make sure we still are
				//only scroll if new comments added, to prevent flickering
				if (isAtBottom && addedAmount > 0)
				{
					commentsUL.animate({ scrollTop: commentsUL.prop("scrollHeight") }, 200);
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

	function setListHeight() {
		$('#commentsList').height(window.innerHeight - $('#CommentBar').outerHeight() - 20);
	}

	function setBarWidth(){
		var form = $('#CommentBar > form');
		form.find('input[name="CommentText"]').width(form.find('select[name="Mood"]').position().left - 25);
	}
 });