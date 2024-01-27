<?php

include 'authenticate.php';


?>
<!DOCTYPE html>
<html>

<head>
	<!-- Sweet Alert -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<title>Tennis Rating Online - Home</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">Tennis Rating Online</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
			aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<li class="nav-item active">
					<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="addMatch.php">Add new match</a>
				</li>
				<?php if (isset($_SESSION['loggedin'])) { ?>
					<li class="nav-item">
						<a class="nav-link" href="logout.php">Logout</a>
					</li>
					<li class="nav-item">
						<p class="nav-link" href="#">Welcome back,
							<?php echo $_SESSION['name']; ?>
						</p>
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
	<?php if (isset($_SESSION['loggedin'])) { ?>
		<p style="text-align: center;">Hey,
			<?php echo $_SESSION['name'] ?>! Here's your customized rank based on the matches you've added:
		</p>

		<?php

		// Simply returning how much elo they have, could be more user-friendly and creating a customized number based on their elo
	
		// Also, center is a really depcreated way to center HTML tags, but I suck at CSS, so here we are...
	
		echo "<center><h1 style='padding-top: 55px;'>" . $_SESSION['elo'] . "</h1><br />";
		?>

		<p onclick="score()" style="cursor: pointer; color: dodgerblue">What does this number mean?</p>
		<p onclick="help()" style="cursor: pointer; color: dodgerblue">How can I improve my rating?</p>
		</center>

		<?php

	}
	?>

</body>

<script>

	function score() {

		swal({
			title: "Score guidelines:",
			text: "This score will start to reflect your true skill level more and more as you enter in more matches. \n <800 - Novice \n 801-1200 - Beginner \n 1201-1600 - Intermediate \n 1601-2000 - Advanced \n 2000-2600 - Competitive Player \n 2601> - Tour Pro",
			icon: "info",
		});

	}

	function help() {

		swal({
			title: "How to improve your rating:",
			text: "Playing lots of matches against other players will help us to accurately place you in a skill bracket, and allow you to play more and more competitive matches that will help you improve. The more matches you enter in, the more accurately we can rate your skill level.",
			icon: "info",
		});

	}

</script>

<!-- Scripts by Bootstrap -->

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
	integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
	crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
	integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
	crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
	integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
	crossorigin="anonymous"></script>

</html>