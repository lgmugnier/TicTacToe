<?php
	$to = "mugnier@bu.edu";
	$subject = "Test mail";
	$message = "Hello! This is a simple email message.";
	$from = "peachgummies@gmail.com";
	$headers = "From:" . $from;
	mail($to,$subject,$message,$headers);
	echo "Mail Sent.";
?> 