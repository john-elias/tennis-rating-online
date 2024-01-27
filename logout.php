<?php

session_start();

if(isset($_SESSION['loggedin'])) {

	unset($_SESSION['loggedin']);

	unset($_SESSION['id']);

	header("Location: home.php");

}