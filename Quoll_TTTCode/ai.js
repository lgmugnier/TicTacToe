$(document).ready(function(){

	var moves=new Array();
	var aiChoice;
	var end;

	$('#menuBar .playai').on("click", function (event) 
	{
		popBoard();
		drawX();
	});
	
	function popBoard()
	{
		$("#player2name").text("AI");
			
		$("#gameTypeBanner").text("VS. AI");
		
		$("#player1_X_O").empty();
		$("#player1_X_O").append("<img style = 'float : left' src='img/assignx_light.png'>");
		$("#player2_X_O").empty();
		$("#player2_X_O").append("<img style = 'float : left' src='img/assigno_dark.png'>");
	}

	function checkWin() 
	{ 
		// CHECKS IF X WON
		if ((moves[0] == moves[1] && moves[0] == moves[2] && (moves[0] == "x")) || //first row
		(moves[3] == moves[4] && moves[3] == moves[5] && (moves[3] == "x")) || //second row
		(moves[6] == moves[7] && moves[6] == moves[8] && (moves[6] == "x")) || //third row
		(moves[0] == moves[3] && moves[0] == moves[6] && (moves[0] == "x")) || //first column
		(moves[1] == moves[4] && moves[1] == moves[7] && (moves[1] == "x")) || //second column
		(moves[2] == moves[5] && moves[2] == moves[8] && (moves[2] == "x")) || //third column
		(moves[0] == moves[4] && moves[0] == moves[8] && (moves[0] == "x")) || //diagonal 1
		(moves[2] == moves[4] && moves[2] == moves[6] && (moves[2] == "x")) //diagonal 2
		) 
		{
			alert("You won!");
			clear();
			return true;
		} 
		
		else
		{ 
			// CHECKS IF O WON
			if ((moves[0] == moves[1] && moves[0] == moves[2] && (moves[0] == "o")) || //first row
			(moves[3] == moves[4] && moves[3] == moves[5] && (moves[3] == "o")) || //second row
			(moves[6] == moves[7] && moves[6] == moves[8] && (moves[6] == "o")) || //third row
			(moves[0] == moves[3] && moves[0] == moves[6] && (moves[0] == "o")) || //first column
			(moves[1] == moves[4] && moves[1] == moves[7] && (moves[1] == "o")) || //second column
			(moves[2] == moves[5] && moves[2] == moves[8] && (moves[2] == "o")) || //third column
			(moves[0] == moves[4] && moves[0] == moves[8] && (moves[0] == "o")) || //diagonal 1
			(moves[2] == moves[4] && moves[2] == moves[6] && (moves[2] == "o")) //diagonal 2
			) 
			{
				alert("Sorry, you lose!");
				clear();
			} 
			
			else 
			{ 
			// CHECKS FOR TIE GAME IF ALL CELLS ARE FILLED
				if (((moves[0] == "x") || (moves[0] == "o")) && ((moves[3] == "x") || 
				(moves[3] == "o")) && ((moves[6] == "x") || (moves[6] == "o")) && ((moves[1] == "x") || 
				(moves[1] == "o")) && ((moves[4] == "x") || (moves[4] == "o")) && ((moves[7] == "x") || 
				(moves[7] == "o")) && ((moves[2] == "x") || (moves[2] == "o")) && ((moves[5] == "x") || 
				(moves[5] == "o")) && ((moves[8] == "x") || (moves[8] == "o"))) 
				{
					alert("It's a tie!");
					clear();
				}
				else
					return false;
			}
		}
	}

	function clear()
	{
		for (var i=0; i<9; i++)
			moves[i]="";
			
		$(".box").html("");
		$(".box").attr("data-occupied", "false");
		
		$("#player2name").empty();
		$("#player1_X_O").empty();
		$("#player2_X_O").empty();
		
		$("#gameTypeBanner").empty();
		
		$("#gameboard .box").off("click");
	}
	
	function drawX()
	{
		$("#gameboard .box").on("click", function()
		{
			if ($(this).attr("data-occupied") == "false")
			{
				$(this).append("<img src = 'img/ttt_x_small.png'>");
				$(this).attr("data-occupied","true");
				var boxnum = $(this).attr("class").split(" ") [1];
				boxnum = boxnum.charAt(1);
				moves[boxnum - 1] = "x";
				if (!checkWin())
					drawO();
			}
			else if($(this).attr("data-occupied") == "true")
				alert("Invalid Move");
		});
	}
	
	function drawO()
	{	
		aiChoice = Math.floor(Math.random() * (8 - 0 + 1)) + 0;
		var counter = aiChoice + 1;
		if ($(".b"+counter).attr("data-occupied") == "false")
		{
			moves[aiChoice] = "o";
			$(".b" + counter).append("<img src = 'img/ttt_o_small.png'>");
			$(".b" + counter).attr("data-occupied","true");
			checkWin();
		}
		else
			drawO();
	}
});