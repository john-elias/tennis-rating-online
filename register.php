
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
	        <a class="nav-link" href="home.php">Home</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="addMatch.php">Add new match</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="login.php">Login</a>
	      </li>
	      <li class="nav-item active">
	        <a class="nav-link" href="register.php">Register</a><span class="sr-only">(current)</span>
	      </li>
	    </ul>
	  </div>
	</nav>
	<div id="form">
		<h2>Register</h2>
		<form method="post" action="register.php">
			<div class="form-group">
				<label for="flname">First/Last Name</label>
				<input type="text" id="flname" name="flname" class="form-control" placeholder="Jane Doe" style="text-align: center;" />
			</div>
			<div class="form-group">
				<label for="username">Username (5+ Characters)</label>
				<input type="text" id="username" name="username" class="form-control" style="text-align: center;" />
			</div>
			<div class="form-group">
				<label for="password">Password (8+ Characters)</label>
				<input type="password" id="password" name="password" class="form-control" style="text-align: center;" />
			</div>
			<button type="submit" name="submit" class="btn btn-primary mb-2">Register</button>
			<br />
			<?php 

include 'authenticate.php';

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) {

	header("Location: home.php");

}

$errors = [];

if(isset($_POST['submit'])) {

	// I've created verification checks so that I want to see if the string length of these variables (username and password) are over 5 and 8 characters. What if it's three characters, I don't want the "Please enter a username" error to fire, I only want the "Your username must be at least five characters!" to fire.

	$usernameSet = FALSE;

	$passwordSet = FALSE;

	if(strlen($_POST['username']) < 5 && strlen($_POST['username']) != 0) {

		array_push($errors, "Your username must be at least five characters!");

		$usernameSet = TRUE;

	}

	if(strlen($_POST['password']) < 8 && strlen($_POST['password']) != 0) {

		array_push($errors, "Your password must be at least eight characters!");

		$passwordSet = TRUE;

	}

	if(empty($_POST['flname'])) {

		array_push($errors, "Please enter a first/last name!");

	}

	if(empty($_POST['username']) && $usernameSet == FALSE) {

		array_push($errors, "Please enter a username!");

	}

	if(empty($_POST['password']) && $passwordSet == FALSE) {

		array_push($errors, "Please enter a password!");

	}

	if(empty($errors)) {

		$flname = htmlentities($_POST['flname']);

		$username = htmlentities($_POST['username']);

		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		// All users start with 1000 elo

		$elo = 1000;

		$registerSql = $con->prepare("INSERT INTO accounts (flname, username, password, elo) VALUES (?, ?, ?, ?)");

		$registerSql->bind_param('sssi', $flname, $username, $password, $elo);

		$registerSql->execute();

		header("Location: home.php");

	}

}
	// Echos all errors here

	foreach($errors as $error) {

		echo $error . "<br /><br />";

	} 

	?>
		</form>
	</div>
	<br />
</body>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</html>