<?php

//Function to encrypt the mail in order to generate a token
function encrypt($data){
    $encdata = password_hash($data, PASSWORD_DEFAULT);
	return $encdata;
}

//Function to verify the token in order to add/remove user
function decrypt($data,$hash){
	$verify = password_verify($data, $hash);
	return $verify;
}
?>