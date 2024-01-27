<?php

// vendor/autoload allows libraries to load into PHP

require_once 'vendor/autoload.php';

include 'authenticate.php';

authenticate($_SESSION['name'], $con);

// PHP Elo system created by https://github.com/zelenin/elo by zelenin; Referencing the player and match classes

use Zelenin\Elo\Player;

use Zelenin\Elo\Match;

// Errors array; most efficient way for error-handling in PHP; array_push()

$errors = [];

// Checks if submit button was clicked to avoid isset notices

if(isset($_POST["submit"])) {


	if(strlen($_POST['score']) != 3) {

		array_push($errors, "Please specify a set 1 score in the format Player1Score-Player2Score!");

	}

	if(empty($_POST['player2'])) {

		array_push($errors, "Please enter a player 2 name!");

	}

	if(empty($errors)) {

		// Retrieving Elo from database

		$getElo1 = $con->prepare("SELECT * FROM accounts WHERE username=?");

		$getElo1->bind_param('s', $_SESSION['name']);

		$getElo1->execute();

		$player1Elo = $getElo1->get_result();

		$getElo2 = $con->prepare("SELECT * FROM accounts WHERE username=?");

		// Currently no regex validation of what the user puts in, if they input something like a-b, it will cause some issues and cause the code to crash. However, collegeboard doesn't require completely 100% secure code in a first year comp-sci class, and here's me being minimalistic lol

		$getElo2->bind_param('s', $_POST['player2']);

		$getElo2->execute();

		$player2Elo = $getElo2->get_result();

		// Player one will ALWAYS be in a row bc name is predetermined, but player 2 may not exist

		if($player2Elo->num_rows > 0) {

			$player1Elo = $player1Elo->fetch_assoc();

			$player2Elo = $player2Elo->fetch_assoc();

			// Creating player variables

			$player1 = new Player($player1Elo['elo']);

			$player2 = new Player($player2Elo['elo']);

			// Calculating # of games won

			$match = new Match($player1, $player2);

			$score = explode("-", $_POST['score']);

			$match->setScore(intval($score[0]), intval($score[1]))
			->setK(32)
			->count();

			$player1 = $match->getPlayer1();

			$player2 = $match->getPlayer2();

			// Fetches the new player ratings after the score has been inputted.

			$newRating1 = $player1->getRating();

			$newRating2 = $player2->getRating();

			$changeElo1 = $con->prepare("UPDATE accounts SET elo = ? WHERE username = ?");

			$changeElo1->bind_param('is', $newRating1, $_SESSION['name']);

			$changeElo1->execute();

			$changeElo2 = $con->prepare("UPDATE accounts SET elo = ? WHERE username = ?");

			$changeElo2->bind_param("is", ceil($newRating2), $_POST['player2']);

			$changeElo2->execute();

			// Rounds the new rating up and shows it to the player; ELO deals with lots of digits beyond the period

			$_SESSION['elo'] = ceil($newRating1);

			header("Location: home.php");
		
		} else {

			array_push($errors, htmlentities($_POST['player2']) . " does not have an account in Tennis Rating Online!");

		}
	}

}
 ?>
<!DOCTYPE html>
<html>
<head>
	<style>
		#form {
			text-align: center;
			padding: 30px;
		}
	</style>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	<title>Tennis Rating Online - Home</title>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  <a class="navbar-brand" href="#">Tennis Rating Online</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarNav">
	    <ul class="navbar-nav">
	      <li class="nav-item">
	        <a class="nav-link" href="home.php">Home </a>
	      </li>
	      <li class="nav-item active">
	        <a class="nav-link" href="#">Add new match</a><span class="sr-only">(current)</span>
	      </li>
	      <?php if($_SESSION['loggedin'] == TRUE) { ?>
	      <li class="nav-item">
	      	<a class="nav-link" href="logout.php">Logout</a>
	      </li>
	      <li class="nav-item">
	      	<p class="nav-link">Welcome back, <?php echo $_SESSION['name']; ?></p>
	      </li>
	  <?php } else { ?>
	      <li class="nav-item">
	        <a class="nav-link" href="login.php">Login</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="register.php">Register</a>
	      </li>
	  <?php } ?>
	    </ul>
	  </div>
	</nav>
	<br />
	<div id="form">
		<h2>Add Match</h2>
		<form method="post" action="addMatch.php">
			<div class="form-group">
				<label for="player1">Player 1</label>
				<input type="text" style="text-align: center;" name="player1" id="player1" class="form-control" disabled="disabled" value="<?php echo $_SESSION['name']; ?>">
				<br />
				<label for="player2">Player 2</label>
				<input type="text" style="text-align: center;" name="player2" id="player2" class="form-control">
				<br />
				<label for="score">How many games did you and your opponent win? (Make sure to include your score first!)</label>
				<input type="text" id="score" name="score" class="form-control" style="text-align: center;" />
				<p>Example:  8-3 or 4-6 or 2-4</p>
			</div>
			<button type="submit" name="submit" class="btn btn-primary mb-2">Add Match</button>
			<br />
			<?php foreach($errors as $error) { echo $error . "<br />"; } ?>
		</form>
	</div>
</body>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</html>