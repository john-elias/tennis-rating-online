<?php

session_start();

// TOR localhost database info

define("DATABASE_HOST", 'localhost');

define("DATABASE_USER", 'root');

define("DATABASE_PASS", '');

define("DATABASE_NAME", 'tor');

// Connection const

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

// Basic error-checking

if(!$con) {

	echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;

}

function authenticate($name, $con) {

	/*
	 Checks if user is logged in, if FALSE; take them to login page, if TRUE; increase times they've logged in
	*/

	if($_SESSION['loggedin'] == FALSE) {

		header("Location: login.php");

	}

	$timesLoggedIn = $con->prepare("SELECT timesloggedin FROM accounts WHERE username=?");

    $timesLoggedIn->bind_param("s", $name); 

    $timesLoggedIn->execute();

    $count = $timesLoggedIn->get_result();

    $count = $count->fetch_assoc();

    $newCount = $count['timesloggedin'] += 1;

	$countIncrease = $con->prepare("UPDATE accounts SET timesloggedin=? WHERE username=?");

	$countIncrease->bind_param("is", $newCount, $name);

	$countIncrease->execute();
	
}