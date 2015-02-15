<?php
//tttgame class provides an interface with which to communicate to the tttgame table in the tttdatabase
//tttgame(playerX varchar (20), playerO varchar(20), moves varchar(17), gametype varchar(6), score varchar(10), activeplayer varchar(20))
//playerX and playerO are foreign keys that reference playerinfo(username)
//moves is a 9 char long variable for holding Xs and Os that hold the game board state.  
//There are 9 characters represent the 9 spaces on the tic tac toe board.

class tttgame
{
	private $user="user";
	private $password="userpassword";
	private $database="tttdatabase";
	private $server="localhost";
	
	//objects must be populated either thru getGame() or beginGame()
	public $playerX;
	public $playerO;
	public $moves;
	public $gametype;
	public $activePlayer;
	public $score;
	public $populated = false;
	public $notice;
	
	//returns playerX
	public function getPlayerX()
	{
		if ($this -> populated == true)
			return $this -> playerX;
		else
			return false;
	}
	
	//return playerO
	public function getPlayerO()
	{
		return $this -> playerO;
	}
	
	//return moves
	public function getMoves()
	{
		if ($this -> populated == true)
			return $this -> moves;
		else
			return false;
	}
	
	//return gametype
	public function getGametype()
	{
		return $this -> gametype;
	}
	
	//return active player
	public function getActivePlayer()
	{
		if ($this -> populated == true)
			return $this -> activePlayer;
		else
			return false;
	}
	
	//return score
	public function getScore()
	{
		return $this -> score;
	}
	
	public function getNotice()
	{
		return $this -> notice;
	}
	
	public function updateScore($newScore)
	{
		$playerX = $this -> playerX;
		$playerO = $this -> playerO;
		$this -> score = $newScore;
		$query = "UPDATE tttGame 
				  SET score = '$newScore' 
				  WHERE playerX = '$playerX' 
				  AND playerO = '$playerO';";
		$this -> queryDB($query);
	}
	
	public function updateNotice($newNotice)
	{
		$playerX = $this -> playerX;
		$playerO = $this -> playerO;
		$this -> notice = $newNotice;
		$query = "UPDATE tttGame 
				  SET notice = '$newNotice' 
				  WHERE playerX = '$playerX' 
				  AND playerO = '$playerO';";
		$this -> queryDB($query);
	}
	
	//switches the active player either from playerX to playerO
	//or from playerO to playerX without input
	//returns nothing
	public function switchActivePlayer()
	{
		$activeplayer = $this -> activePlayer;
		$playerX = $this -> playerX;
		$playerO = $this -> playerO;
		if ($activeplayer == $playerX)
			$this -> activePlayer = $playerO;
		else
			$this -> activePlayer = $playerX;
			
		$newactive = $this -> activePlayer;
		
		$query = "UPDATE tttgame 
				  SET activeplayer = '$newactive' 
				  WHERE playerX = '$playerX' 
				  AND playerO = '$playerO';";
		$this -> queryDB($query);
	}
	
	//populates the game object
	//returns true if there exists a game with specified player, false otherwise
	public function getGame($username)
	{
		$query = "SELECT * 
				  FROM tttgame 
				  WHERE playerX = '$username' 
				  OR playerO = '$username';";
		$result = $this -> queryDB($query);
		
		$game = mysql_fetch_row($result);
		
		if ($game != false && count($game) == 7)
		{
			$this -> playerX = $game[0];
			$this -> playerO = $game[1];
			$this -> moves = $game[2];
			$this -> gametype = $game[3];
			$this -> score = $game[4];
			$this -> activePlayer = $game[5];
			$this -> notice = $game[6];
			$this -> populated = true;
			return true;
		}
		else
			return false;
	}
	
	//creates a new game
	//returns nothing
	public function beginNewGame($playerX, $playerO, $gametype)
	{
		$this -> playerX = $playerX;
		$this -> playerO = $playerO;
		$this -> gametype = $gametype;
		$this -> moves = "---------";
		$activeplayer = $playerX;
		$this -> activePlayer = $activeplayer;
		
		$query = "INSERT INTO tttGame (playerX, playerO, gametype, activeplayer)
			      VALUES ('$playerX', '$playerO', '$gametype', '$activeplayer');";
		$this -> queryDB($query);
	}
	
	//deletes game from database
	public function closeGame()
	{
		$playerX = $this -> playerX;
		$playerO = $this -> playerO;
		$query = "DELETE FROM tttGame 
				  WHERE playerX = '$playerX' 
				  AND playerO = '$playerO';";
		$this -> queryDB($query);
	}
	
	//input new move string
	//replaces old move string in database with input
	//returns nothing
	public function updateMoves($movestring)
	{		
		$playerX = $this -> playerX;
		$playerO = $this -> playerO;
		$this -> moves = $movestring;
		$query = "UPDATE tttGame 
				  SET moves = '$movestring' 
				  WHERE playerX = '$playerX' 
				  AND playerO = '$playerO';";
		$this -> queryDB($query);
	}
	
	//utility function for querying DB
	public function queryDB($query)
	{
		$conn = mysql_connect($this -> server,$this -> user,$this -> password) or die("Unable to connect to MySQL server");
		mysql_select_db($this -> database) or die( "Unable to select database");
		
		$result = mysql_query ($query) or die("Query failed: ".mysql_error());
		return $result;
		
		mysql_close($conn);
	}
}
?>