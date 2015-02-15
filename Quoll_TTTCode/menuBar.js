$(document).ready(function()
{
	//append all images that will appear on the menu bar
	$("#menuBar").append("<img class = 'playrando' style = 'float : left; margin-left : 10px;' src='img/playVSrand.png'>");
	$("#menuBar").append("<img class = 'playai' style = 'float : left;' src='img/playVSai.png'>");
	
	$("#menuBar").append("<img class = 'logout' style = 'float : right; margin-right : 10px;' src='img/Logout.png'>");
	$("#menuBar").append("<img class = 'quit' style = 'float : right; margin-right : 10px;' src='img/quitGame.png'>");
	
	$(".quit").hide();
	
	$("#menuBar .playrando").on("click", function()
	{	
		$.getJSON("getRandomPlayer.php", function(rando)
		{
			if (rando != false)
				$.get("startGame.php?opponent=" + rando + "&type=random");
			else if (rando == false)
				alert("There are no available opponents");
		});
	});	
	
	$("#menuBar .logout").on("click", function()
	{	
		var response = confirm("Are you sure you would like to logout?");
		if (response == true)
		{
			$.post("tttlogout.php");
			window.location.href = 'login.html';
		}
	});

});