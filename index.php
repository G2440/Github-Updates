<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Github Updates</title>
  <link rel="icon" href="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" type="image/x-icon">
</head>

<body style="background-color: #f8f0d5; background-image: url('assets/snaps/octocat.jpg'); background-repeat: no-repeat; background-attachment: fixed; background-size: 100% 100%"> 

  <form class="form" action="" method="POST" style="float:left;" id="check">
    <h1 style="font-size:50px; color :ghostwhite;">Subscribe for Updates</h1>
    <h1 style="font-size:60px; color :ghostwhite;">from </h1>
    <h1 style="font-size:50px; color :ghostwhite;">Github Timeline!</h1>
    
    <br><br>
    
    <div>
      <h4><label for="email" style="color:ghostwhite; font-size:30px;"> Enter Your Email</label></h4>
      <input type="text" id="email" style="width:350px; height:45px; font-size:25px; border-radius:15px;" name="email" placeholder="abc@example.com" required>
    </div>

    <div>
      <button class="button button-primary" style="font-size: 40px;" name="check" type="submit">Submit</button>
    </div>
  </form>

</body>
</html>

<?php
require_once dirname(__FILE__) . '/config/db.php';
require_once dirname(__FILE__) . '/config/secret_api.php';
require_once dirname(__FILE__) . '/assets/helper/encdec.php';


//Checking if the form has been submitted 
if (isset($_POST['check'])){
  if (isset($_POST['email'])){

    //Parsing the Unsanitized Email input by User and sanitizing it
    $mail = strip_tags($_POST['email']);
     
    //Preparing Statement for Picking out the row with given email
    $stmt = mysqli_prepare($conn,'SELECT * FROM users WHERE email= ? ');
    mysqli_stmt_bind_param($stmt,'s', $mail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);


    //Checking if the Email is already in the Database
    if (mysqli_stmt_num_rows($stmt) != 0) {
      echo "<script>alert('Email Already in the database'); window.location.href='index';</script>";
      exit();  
    }

    //body is the variable which will be added to the mail to be sent to the User
    $body = '';

    //Variables for the Image in the Confirmation Mail
    $height = 150;
    $width = 350;
    $link = 'https://csharpcorner-mindcrackerinc.netdna-ssl.com/article/create-github-repository-and-add-newexisting-project-using-github-desktop/Images/github.png';
    
    //Appending the content including the image to the body variable
    $body .= '<div><img src=';
    $body .= '"';
    $body .= $link;
    $body .= '"';
    $body .= 'width=';
    $body .= $width;
    $body .= 'height=';
    $body .= $height;
    $body .= '/>';
    $body .= '</div>';

    $body .= '<h1>Please click on below link for Email ID Verification</h1><br>';

    //Encrypting the user Email to attach it to the Confirm Email URL
    $encryption = encrypt($mail);

    if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)){
      $protocol = 'https://';
    }
    else {
      $protocol = 'http://';
    }

    if(isset($_SERVER['HTTP_HOST'])){
      $server = $_SERVER['HTTP_HOST'];
    }
    
    //Adding complete address to the protocol
    $protocol .= $server;
    $protocol .= '/';
    $protocol .= basename(dirname(__FILE__));
    $protocol .= '/auth/subscribe';

    //Adding the Confirm URL to the body variable

    $body .= '<h3>';
    $body .= '<a href=';
    $body .= '"';
    $body .= $protocol;
    $body .= '?email=';
    $body .= $mail;
    $body .= '&token=';
    $body .= $encryption;
    $body .= '">Confirm Email</a>';
    $body .= '</h3>';

    //USING CURL IN ORDER TO SEND THE MAIL TO THE USER FOR EMAIL VERIFICATION 
    $sendgrid_apikey = API_KEY;
    $url = 'https://api.sendgrid.com/';
    $pass = $sendgrid_apikey;
    
    $params = array(
        'to'        => $mail,
        'from'      => from_mail,
        'fromname'  => from_name,
        'subject'   => 'Confirmation Mail for Updates',
        'html'      => $body,
      );
    
    $request =  $url.'api/mail.send.json';
    
    // Generate curl request
    $session = curl_init($request);

    // Tell PHP not to use SSLv3 (instead opting for TLS)
    curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $sendgrid_apikey));
    
    // Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);
    
    // Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
    
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    
    // obtain response
    $response = curl_exec($session);
    curl_close($session);
    
    //Decoding response to convert it to associative array from string
    $response = json_decode($response,true);
    if($response['message'] == 'success'){
      echo "<script>alert('Email Sent for Verification');</script>";
      echo "<script>window.location.href='index';</script>";
    }
    else{
      echo "<script>alert('Failed to send verification email');</script>";
      echo "<script>window.location.href='index';</script>";   
    }
  }
  else{
    echo "<script>alert('Invalid Request');</script>";
    echo "<script>window.location.href='index';</script>";
  }
}
?>


