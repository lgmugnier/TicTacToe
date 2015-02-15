<?php
	include "friendslist.php";
	
	session_start();
	$username = $_SESSION['username'];
	
	$friend = $_GET['friend'];
	$flist = new friendslist($username);
	$isFriend = $flist -> isFriend($friend);
	//a user cannot add themselves as a friend
	//user cannot add a friend that they are already friends with
	if ($friend != $username && $isFriend == false)
	{
		$flist -> addFriend($friend);
	}
?>