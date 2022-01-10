<?php

    //Checking if it exists
    if(isset($_SERVER['REDIRECT_STATUS'])){
    //Retrieving the Error code
    $code = $_SERVER['REDIRECT_STATUS'];
    //Array for Errors
    $codes = array(
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error'
    );
    //Checking if Error Code exists in the array 
    if(array_key_exists($code, $codes) && is_numeric($code)){
        die("Error $code: {$codes[$code]}");
    }
    else{
        die('Unknown error');
    }
}else{
    echo "<script>alert('Invalid Request');</script>";
    echo "<script>window.location.href='index';</script>";
}
?>