<?php

include "playerinfo.php";

$user = "";
$pass = "";
$email = "";
$errorMessage = "";
$player = new playerinfo();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$user = $_POST['username'];
	$pass = $_POST['password'];
	$email = $_POST['email'];
	
	$playerexists = $player -> exists($user);

		if ($playerexists) //checks if username exists
		{
			echo "<script> alert ('Username already taken.') </script>";
			echo "<script language='Javascript'> window.location.href = 'Register.html' </script>";
		} 
		else //creates user
		{
			$player -> createPlayer($user, $pass, $email); // Confirm order.
			header("Location: Login.html");
		}
}

?>