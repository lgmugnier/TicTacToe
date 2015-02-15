$(document).ready(function()
{	
	generateFriendsList();
	generateLeaderboard();
	window.setInterval(generateFriendsList, 10000);
	window.setInterval(generateLeaderboard, 10000);	
	
	//add autocomplete function to #searchFriend
	$(function() { 
		$("#searchFriend").autocomplete({source:"autoselect.php", minLength:2});
	});
	
	//handles form "submit" used to add friends via autocomplete function
	$(function() {
		$('form').submit(function(e) 
		{
			e.preventDefault(); //prevents page from refreshing
			$.get("addFriend.php", {friend: $("#searchFriend").val()});
			generateFriendsList();
			$("#searchFriend").val("");
		});
	});
	
	//Generates friendslist using generateFriendsList.php which uses friendslist.php to communicate with DB
	//alternates between dark background and light backgrounds, x-images, and the arrow image
	function generateFriendsList()
	{
		$.ajaxSetup({async:false});
		var data = $.getJSON("/generateFriendsList.php", function (data)
		{
			var colormarker = 1;
			var color;
			var arrowimg;
			var ximg;
			var arrowStyle;
			var cssStyle;
			
			var tempFriendsList = document.createElement("div");
			
			$.each(data, function ()
			{
				var friend = this;
				$.getJSON("/isOnline.php?friend=" + friend, function(online)
				{
					$.getJSON("/isInGame.php?username=" + friend, function(ingame)
					{
						if (colormarker % 2 == 0)
						{
							arrowimg = "img/activearrow_light.png";
							ximg = "img/x_light.png";
							color = "#D3D3D3";
						}
						else
						{
							arrowimg = "img/activearrow_dark.png";
							ximg = "img/x_dark.png";
							color = "#9B9B9B";
						}
						
						if (online == false)
						{
							arrowStyle = "visibility : hidden";
							cssStyle = "background-color : " + color + "; color : #619394;";
						}
						else 
						{
							arrowStyle = "";
							cssStyle = "background-color : " + color + ";";
						}
						
						if (ingame == true)
						{
							arrowStyle = "visibility : hidden";
						}
						
						colormarker ++; 
						
						$(tempFriendsList).append("<div class = 'friendentry' style = '" + cssStyle + "'><img class = 'activearrow' data-opp = '"+friend+"'style = '" + 
						arrowStyle + "'src = '" + arrowimg + "'>" + friend + "<img data-friend = '" + friend + "' class = 'x' src = '" + ximg + "'></div>");
					}); 
				});
			});
			
			$("#friendsList").html("");
			$("#friendsList").replaceWith(tempFriendsList);
			tempFriendsList.id = "friendsList";	
			
			$("#friendsList .x").on("click", function()
			{
				var fr = $(this).attr("data-friend");
				$.get("removeFriend.php?friend=" + fr);
				generateFriendsList();
			});
					
			$("#friendsList .activearrow").on("click", function()
			{
				var opponent = $(this).attr("data-opp");
				$.get("startGame.php?opponent=" + opponent + "&type=friend");
				generateFriendsList();
			});	
		});
	}
	
					
	
	function generateLeaderboard()
	{
		$.ajaxSetup({async:false});
		$.getJSON("/generateLeaderboard.php", function (data)
		{
			var color;
			var arrowimg;
			var plusimg;
			var colormarker = 1;
			var arrowStyle;
			var cssStyle;
			
			var tempLeaderboard = document.createElement("div");
			
			$.each(data, function ()
			{
				var friend = this;
				$.getJSON("/isOnline.php?friend=" + friend, function(online)
				{
					$.getJSON("/isInGame.php?username=" + friend, function(ingame)
					{
						if (colormarker % 2 == 0)
						{
							arrowimg = "img/activearrow_light.png";
							plusimg = "img/plus_light.png";
							color = "#D3D3D3";
						}
						else
						{
							arrowimg = "img/activearrow_dark.png";
							plusimg = "img/plus_dark.png";
							color = "#9B9B9B";
						}
						
						if (online == false)
						{
							arrowStyle = "visibility : hidden";
							cssStyle = "background-color : " + color + "; color : #619394;";
						}
						else 
						{
							arrowStyle = "";
							cssStyle = "background-color : " + color + ";";
						}
						
						if (ingame == true)
						{
							arrowStyle = "visibility : hidden";
						}
						
						colormarker ++; 
						
						$(tempLeaderboard).append("<div class = 'leaderentry' style = '" + cssStyle + "'> <img class = 'activearrow' data-opp='"+friend+"' style = '"+ arrowStyle +"' src = '" + arrowimg + "'>" + friend +
							 "<img data-leader = '" + friend + "' class = 'plus' src = '" + plusimg + "'></div>");
					}); 
				});
			});	
			
			$("#leaderboard").replaceWith(tempLeaderboard);
			tempLeaderboard.id = "leaderboard";
			
			$("#leaderboard .plus").on("click", function()
			{
				var fr = $(this).attr("data-leader");
				$.get("addFriend.php?friend=" + fr);
				generateLeaderboard();
				generateFriendsList();
			});
				
			$("#leaderboard .activearrow").on("click", function()
			{
				var opponent = $(this).attr("data-opp");
				$.get("startGame.php?opponent=" + opponent + "&type=leader");
				generateLeaderboard();
			});
		});
	}
});