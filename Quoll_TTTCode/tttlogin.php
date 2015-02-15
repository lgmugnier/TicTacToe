<?PHP

include "playerinfo.php";

$user = "";
$pass = "";
$errorMessage = "";
$player = new playerinfo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$user = $_POST['username'];
	$pass = $_POST['password'];
	
	if ($player -> validLogin($user, $pass)) 
	{
		session_start();
		$_SESSION['login'] = "1";
		$_SESSION['username'] = $user;
		header ("Location: index.html"); // Replace with main page.
		$player -> getPlayer($user);
		$player -> putOnline();
	} 
	else 
	{
		//$errorMessage = "Invalid Username or Password";
		//header ("Location: login.html");
		//echo "<script type = 'text/javascript'>alert('$errormessage');</script>";//this doesn't work yet
		echo "<script> alert ('Invalid Username or Password') </script>";
		echo "<script language='Javascript'> window.location.href = 'login.html' </script>";
	}
}
?>