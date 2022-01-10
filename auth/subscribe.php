<?php

require_once dirname(__FILE__,2) . '/config/db.php';
require_once dirname(__FILE__,2) . '/assets/helper/encdec.php';

//Check if Email and token fields are passed
if(isset($_GET['email']) && isset($_GET['token'])){
//Parsing Unsanitized Email and Unsanitized Token Generated From the URL & Sanitizing them  
$email = strip_tags($_GET['email']);
$decry = strip_tags($_GET['token']);


//If User Token Verifies then proceed 
if(decrypt($email,$decry)){
 
  //Preparing Statments for processing data
  $stmt = mysqli_prepare($conn,'SELECT * FROM users WHERE email= ? ');
  mysqli_stmt_bind_param($stmt,'s', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  //Check if User is already in the database
  if(mysqli_stmt_num_rows($stmt)>0){
    echo "<script>alert('Email Already Subscribed');</script>";
    echo "<script>window.location.href='/php-gn24/index';</script>";
  }
  else{
    //Query to insert the Email in the database
    $stmt1 = mysqli_prepare($conn,'INSERT INTO users(email) VALUES (?)');
    mysqli_stmt_bind_param($stmt1,'s', $email);
    $q = mysqli_stmt_execute($stmt1);
    
    if($q){ 
      echo "<script> alert('Subscribed successfully!');</script>";
    }
    else{
      echo "<script>alert('Failed to Subscribe')</script>";
      echo "<script>window.location.href='/php-gn24/index';</script>";
    }
    
    //Redirect to Page Informing Successful Registration 
    echo "<script>window.location.href='/php-gn24/successful.htm';</script>";
  } 
}
else{
  //Error Occurred so redirect to home page
  echo "<script>alert('Failed to verify your email')</script>";
  echo "<script>window.location.href='/php-gn24/index';</script>";
}
}
//URL isn't correct
else{
  echo "<script>alert('Invalid Request')</script>";
  echo "<script>window.location.href='/php-gn24/index';</script>";
  
}
?>

