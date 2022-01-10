<?php
require_once dirname(__FILE__,2) . '/config/db.php';
require_once dirname(__FILE__,2) . '/assets/helper/encdec.php';

//Checking if ID & token are passed 
if(isset($_GET['id']) && isset($_GET['token'])){
	
	//Parsing Unsanitised ID and Unsanitized Token from the URL	and Sanitizing them
	$id=strip_tags($_GET['id']);
	$decry = strip_tags($_GET['token']);

	//Preparing Statement for finding the email with respective ID
	$stmt3 = mysqli_prepare($conn,'SELECT email FROM users WHERE id = ? ');
    mysqli_stmt_bind_param($stmt3,'i', $id);
    mysqli_stmt_execute($stmt3);
    $result = mysqli_stmt_get_result($stmt3);
   


	//If no user with given ID is available
	if(mysqli_num_rows($result)>0){

		$row = mysqli_fetch_assoc($result);
	
		//Verifying the User Email with Token to proceed
		if(decrypt($row['email'],$decry)){

			//Query to Delete the Record of the parsed ID
			$stmt4 = mysqli_prepare($conn,'DELETE FROM users WHERE id = ?');
			mysqli_stmt_bind_param($stmt4,'i', $id);
			$worked = mysqli_stmt_execute($stmt4);
			
			//On Successful Execution of Query
			if($worked){
				echo "<script> alert('Unsubscribed successfully!');</script>";
				echo "<script>window.location.href='/php-gn24/index';</script>";
			}
			else{
				echo "<script> alert('Error Deleting from Database');</script>";
				echo "<script>window.location.href='/php-gn24/index';</script>";		   
			}
}
//If Token doesn't verify	
else{
	echo "<script>alert('User Cannot be Verified');</script>";
	echo "<script>window.location.href='/php-gn24/index';</script>";
}	
}
else{
	echo "<script>alert('No Such User Available');</script>";
	echo "<script>window.location.href='/php-gn24/index';</script>";
}
}
//If ID isn't found in URL
else{
	echo "<script>alert('Invalid Request');</script>";
	echo "<script>window.location.href='/php-gn24/index';</script>";
}
?>