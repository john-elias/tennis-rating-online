<?php 
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
	        <a class="nav-link" href="home.php">Home</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="addMatch.php">Add new match</a>
	      </li>
	      <li class="nav-item active">
	        <a class="nav-link" href="login.php">Login</a><span class="sr-only">(current)</span>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="register.php">Register</a>
	      </li>
	    </ul>
	  </div>
	</nav>
	<div id="form">
		<h2>Log In</h2>
		<form method="post" action="login.php">
			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" class="form-control" placeholder="TennisEnthusiast" style="text-align: center;" />
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" class="form-control" style="text-align: center;" />
			</div>
			<button type="submit" name="submit" class="btn btn-primary mb-2">Log In</button>
			<br />
			<?php 
include 'authenticate.php';
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) {
	header("Location: home.php");
}
$errors = [];

if(isset($_POST['submit'])) {
	if(empty($_POST['username'])) {
		array_push($errors, "Please enter a username!");
	}
	if(empty($_POST['password'])) {
		array_push($errors, "Please enter a password!");
	}
	if(empty($errors)) {
		if ($stmt = $con->prepare('SELECT id, password, elo FROM accounts WHERE username = ?')) {
			$stmt->bind_param('s', $_POST['username']);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($id, $password, $elo);
				$stmt->fetch();
				function authenticateUser($id, $password, $elo) {

					if (password_verify($_POST['password'], $password)) {

						session_regenerate_id();

						$_SESSION['loggedin'] = TRUE;

						$_SESSION['name'] = $_POST['username'];

						$_SESSION['id'] = $id;

						$_SESSION['elo'] = $elo;

						header("Location: home.php");

					} else {

						echo "Incorrect username or password";

					}

				}

				authenticateUser($id, $password, $elo);

				

			} else {
				echo "Incorrect username or password";
			}

			$stmt->close();
		}
	}
}
			foreach($errors as $error) {
				echo $error . "<br /><br />";
			} ?>
		</form>
	</div>
	<br />
</body>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</html>