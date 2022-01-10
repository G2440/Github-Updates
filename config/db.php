<?php
require_once dirname(__FILE__) . '/secrets.php';

//Establishing Connection with the Database
$conn=mysqli_connect($host,$user,$pass,$db);

//Check if there is any errors	
$code = mysqli_connect_errno(); 
if ($code)
		  {
		  echo 'Connection Failed to MySQL: ' .$code;
		  }
?>