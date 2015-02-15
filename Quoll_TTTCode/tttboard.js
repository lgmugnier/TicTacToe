$(document).ready(function()
{
	var checkinGame = window.setInterval(inGame, 1000);
	var drawInterval;
	var noticeListenerInterval;
	var wintielose;

	//click Handlers
	$("#menuBar .quit").on("click", function()
	{
		var response = confirm("Are you sure you would like \n to quit your current game?  \n It will count as a loss");
		if (response == true)
		{
			wintielose = "quit";
			clearInterval(drawInterval);
			clearInterval(noticeListenerInterval);
			$.get("exitGame.php"); //sets ingame to 0
			$.get("updateNotice.php?notice=quit");
			$.get("delegateLoss.php");
			clear();
			checkinGame = window.setInterval(inGame, 1000);
		}
	});
	
	$("#gameboard .box").on("click", function()
	{
		play(this);
	});
	
	//used for loop to check if user is in game then set up board
	function inGame()
	{
		clear();
		$.getJSON("inGame.php", function(ingame)
		{
			if (ingame == true)
			{
				$(".quit").show('slide', {direction: 'right'}, 1000);
				clearInterval(checkinGame);
				popBoard();
				checkTieInterval = window.setInterval(noticeListener, 1000);
				drawInterval = window.setInterval(draw, 1000);
				wintielose = "";
			}
		});
	}
	
	//populates board with gametype, opponents player stats, show X and O assignments, game scores
	function popBoard()
	{
		wintielose = "";
		$.getJSON("getOpponent.php", function(opponent)
		{
			$.getJSON("returnPlayerStats.php?username=" + opponent, function(stats)
			{
				var name = stats[0];
				var wins = stats[1];
				var losses = stats[2];
				var ties = stats[3];
				var total = stats[4];
				var rank = stats[5];
				var standing = stats[6];
				$("#player2name").text(name);
				$("#player2stats").text("W " + wins + "     L " + losses + "     T " + ties + "     R " + rank);
			});
			
			$.getJSON("getXO.php?username", function(xo)
			{	
				$("#player1_X_O").empty();
				$("#player1_X_O").append("<img style = 'float:left' src='img/assign" + xo + "_light.png'>");
				
				$.getJSON("getGameScore.php", function(score)
				{
					var split = score.indexOf(',');
					if (xo == "x")
					{
						$("#player1scoreBanner").text(score.substring(0,split));
						$("#player2scoreBanner").text(score.substring(split + 1));
					}
					else
					{
						$("#player2scoreBanner").text(score.substring(0,split));
						$("#player1scoreBanner").text(score.substring(split + 1));
					}
				});
			});
			
			$.getJSON("getXO.php?username="+opponent, function(xo1)
			{
				$("#player2_X_O").empty();
				$("#player2_X_O").append("<img style = 'float:left' src='img/assign" + xo1 + "_dark.png'>");
			});
		});
		
		$.getJSON("getGametype.php", function(gametype)
		{
			$("#gameTypeBanner").text("vs. " + gametype);
		});
		
	}
	
	//draw gameboard
	function draw()
	{
		if (wintielose == "win")
			return;
			
		$.getJSON("getMoves.php", function(moves)
		{
			if (moves == false)
				clear();
			for (var x = 0; x < 9; x++)
			{
				var counter = x + 1;
				if (moves[x] != "-")
				{
					if (moves[x] == "x")
					{
						$(".b" + counter).html("");
						$(".b" + counter).append("<img src = 'img/ttt_x_small.png'>");
					}
					else if (moves[x] == "o")
					{
						$(".b" + counter).html("");
						$(".b" + counter).append("<img src = 'img/ttt_o_small.png'>");
					}
					$(".b" + counter).attr("data-occupied","true");
				}
			}
		});
	}
	
	//checks if active player, valid move, draw X/O, update moves
	function play(box)
	{		
		var isActive = $.getJSON("isActivePlayer.php",function(isActive)
		{
			if (isActive == true)
			{
				if ($(box).attr("data-occupied") == "false")
				{
					var XO = $.getJSON("getXO.php?username", function(XO)
					{
						var moves = $.getJSON("getMoves.php", function(moves)
						{	
							if (moves != false)
							{
								var boxnum = $(box).attr("class").split(" ") [1];
								boxnum = boxnum.charAt(1);
								moves[boxnum - 1] = XO;
								moves = moves.toString();
								moves = moves.replace(/,/g,"");
								draw();
								$.get("updateMoves.php?moves=" + moves);
								checkWin();
							}
						});
					});
					$.get("switchActivePlayer.php");
				}
				else
					alert("This is not a valid move");
			}
			else if (isActive == false)
				alert("It is not your turn.");
		});
	}
	
	//checks for changes in opponent's game state (quit, win, tie, lose, rematch requests and responses)
	function noticeListener()
	{		
		draw();
		
		if (wintielose == "quit")
			return;
			
		$.getJSON("getNotice.php", function(notice)
		{
			switch (notice)
			{
				case "approve":
					if (wintielose != "waiting approval")
						return;
					drawInterval = window.setInterval(draw, 1000);
					clear();
					popBoard();
					$.get("updateNotice.php?notice=request");
					break;
				
				case "reject":
					if (wintielose != "waiting approval")
						return;
					$.get("endGame.php");
					clearInterval(noticeListenerInterval);
					clearInterval(drawInterval);
					clear();
					wintielose = "";
					checkinGame = window.setInterval(inGame, 1000);
					break;
					
				case "quit":
					if (wintielose == "waiting approval" || wintielose == "win")
						return;
					alert("You're opponent has quit");
					$.get("endGame.php");
					clearInterval(noticeListenerInterval);
					clearInterval(drawInterval);
					clear();
					checkinGame = window.setInterval(inGame, 1000);
					break;
					
				case "tie":
					if (wintielose == "waiting approval" || wintielose == "win")
						return;
					alert("You Tied!");
					$.get("endGame.php");
					youTied();
					break;
					
				case "loss":
					if (wintielose == "waiting approval" || wintielose == "win")
						return;
					alert("You Lost!");
					$.get("endGame.php");
					youLost();
					break;
						
				case "tier":
					if (wintielose == "waiting approval" || wintielose == "win")
						return;
					alert("You've Tied!");
					var choice = confirm("Rematch?");							
					if (choice == false)
					{
						wintielose = "win";
						youTied();
						$.get("updateNotice.php?notice=reject");
						clear();
					}
					else
						approveRematch();
					break;				
				
				case "lossr":
					if (wintielose == "waiting approval" || wintielose == "win")
						return;
					alert("You've Lost!");
					var choice = confirm("Rematch?");
					if (choice == false)
					{
						wintielose = "win";
						youLost();
						$.get("updateNotice.php?notice=reject");
						clear();
					}
					else
						approveRematch();
			}
		});
	}
	
	function approveRematch()
	{
		$.get("updateNotice.php?notice=approve");
		$.get("updateMoves.php?moves=---------");
		wintielose = "win";
		clear();
		popBoard();
	}
	
	//checks current board state against tic-tac-toe win or tie conditions
	function checkWin()
	{
		var moves = $.getJSON("getMoves.php", function(moves)
		{
			if (moves != false)
			{
				if (moves.indexOf("-") == -1)
				{
					alert("You Tied!");
					var choice = confirm("Rematch?");
					if (choice == false)
					{
						youTied();
					}
					else
					{
						$.get("addTietoScore.php");
						$.get("updateNotice.php?notice=tier");
						wintielose = "waiting approval";
						
						//clearInterval(drawInterval);
						//clear();
						//popBoard();
					}
				}
					
				// vertical win
				if (moves[0] == moves [3] && moves[0] == moves[6] && moves[3] == moves[6] && moves[0] != "-")
					youWon();
				if (moves[1] == moves[4] && moves [4] == moves[7] && moves[1] == moves[7] && moves[1] != "-")
					youWon();
				if (moves[2] == moves [5] && moves[2]== moves[8] && moves[5] == moves[8] && moves[2] != "-")
					youWon();
					
				//check horizontal win
				if (moves[0] == moves[1] && moves[0] == moves[2] && moves[2] == moves[1] && moves[0] != "-")
					youWon();
				if (moves[3] == moves [4] && moves[3] == moves[5] && moves[4] == moves[5] && moves[3] != "-")
					youWon();
				if (moves[6] == moves[7] && moves[6] == moves[8] && moves[8] == moves[7] && moves[6] != "-")
					youWon();
					
				//check diagonal win
				if (moves[0] == moves[4] && moves[0] == moves[8] && moves[4] == moves[8] && moves[0] != "-")
					youWon();
				if (moves[2] == moves[4]  && moves[2] == moves[6] && moves[4] == moves[6] && moves[2] != "-")
					youWon();
			}
		});
	}
	
	//executes if current player won
	function youWon()
	{
		draw();
		wintielose = "win";
		alert("You've won!");
		$.get("delegateWin.php");
		var choice = confirm("Rematch?");
		if (choice == false)
		{
			clearInterval(noticeListenerInterval);
			$.get("updateNotice.php?notice=loss");
			clearInterval(drawInterval);
			$.get("exitGame.php");//sets ingame to 0
			clear();
			checkinGame = window.setInterval(inGame, 1000);
		}
		else
		{
			$.get("updateNotice.php?notice=lossr");
			$.get("addWintoScore.php");
			wintielose = "waiting approval";
		}
	}
	
	//executes if current player tied
	function youTied()
	{
		if (wintielose != "win")
			return;
		clearInterval(noticeListenerInterval);
		$.get("delegateTie.php");
		$.get("updateNotice.php?notice=tie");
		clearInterval(drawInterval);
		clear();
		checkinGame = window.setInterval(inGame, 1000);
	}
	
	//executes if current player lost
	function youLost()
	{
		if (wintielose == "win")
			return;
		clearInterval(noticeListenerInterval);
		$.get("delegateLoss.php");
		clearInterval(drawInterval);
		clear();
		checkinGame = window.setInterval(inGame, 1000);
	}
	
	//clears all information from popBoard()
	function clear()
	{
		$("#player2name").empty();
		$("#player2stats").empty();
		$("#player2_X_O").empty();
		$("#player1_X_O").empty();
		
		$(".box").empty();
		$(".box").attr("data-occupied", "false");
		$("#gameTypeBanner").empty();			
		$("#player1scoreBanner").empty();
		$("#player2scoreBanner").empty();
		
		$(".quit").hide();
	}
	
});