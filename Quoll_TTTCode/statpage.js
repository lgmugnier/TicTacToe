$(document).ready(function()
{	
	$("#player1statsPageName").hide();
	$("#player1statsPage").hide();
	$("#player1statsPage2").hide();
	$("#player1statsPageRank").hide();

	$(".extendarrow").attr("src", "img/extendarrow_light.png");
	
	$(".extendarrow").click(function()
	{
		$("#player1_X_O").hide();
		$("#player2Banner").hide();
		$("#scoreBanner").hide();
		$(".extendarrow").hide();
		$("#player1name").hide();
		$("#player1stats").hide();
		$("#gameboard").hide();
		$("#player1Banner").animate({height : '100%', width : '100%'});
		$("#player1Banner").append("<img class = 'hidearrow' src = 'img/hidearrow_light.png'>");
		$(".hideArrow").insertBefore("#player1statsPageName");
		$("#player1statsPageName").show();
		$("#player1statsPage").show();
		$("#player1statsPage2").show();
		$("#player1statsPageRank").show();
		
		$(".hidearrow").click(function()
		{
			$("#player1Banner").animate({height : '100px', width : '49.5%'});
			$("#player1_X_O").show();
			$("#player1name").show();
			$("#player1stats").show();
			$("#player2Banner").show();
			$("#gameboard").show();
			$("#scoreBanner").show();
			$(".extendarrow").show();
			$(".hidearrow").hide();
			
			$("#player1statsPageName").hide();
			$("#player1statsPage").hide();
			$("#player1statsPage2").hide();
			$("#player1statsPageRank").hide();
		});
	});	
	
	var stats = $.getJSON("returnPlayerStats.php?username=this", function(stats)
	{
		var name = stats[0];
		var wins = stats[1];
		var losses = stats[2];
		var ties = stats[3];
		var total = stats[4];
		var rank = stats[5];
		var standing = stats[6];
		$("#player1name").text(name);
		$("#player1stats").text("W " + wins + "     L " + losses + "    T " + ties + "     R " + rank);
		
		$("#player1statsPageName").text(name);
		$("#player1statsPage").html("WIN </br>" + "<br>LOSE </br>" + "<br>TIE </br>"+ "<br>STANDING </br>");
		$("#player1statsPage2").html(wins + "</br><br>" + losses + "</br><br>" + ties + "</br><br>" + standing + "</br>"); 
		$("#player1statsPageRank").html("RANK" + "<br><font size='7'>" + rank);
	});
});