<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Github Updates</title>
    <link rel = "icon" href = "https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" type = "image/x-icon">
</head>
<body>
</body>
</html>

<?php
require_once dirname(__FILE__) . '/config/db.php';
require_once dirname(__FILE__) . '/config/secret_api.php';
require_once dirname(__FILE__) . '/assets/helper/encdec.php';


//Retrieving the last time mails were sent to user 
$stmt5 = mysqli_prepare($conn,'SELECT cron_time FROM cron_work');
mysqli_stmt_execute($stmt5);
$cron_time   = strtotime('now');
$result = mysqli_stmt_get_result($stmt5);

if (mysqli_num_rows($result)>0){
    $arr = mysqli_fetch_assoc($result);
    $cron_time = $arr['cron_time'];
}        



//This a time-check for cron which ensures that mails are sent on a 5-minute 
// time schedule and no one can launch a Denial of Service attack by Bruteforce.
//This time-check checks if the duration b/w last time of cron in the database
//and current time is more than or equal to 5 minutes it will allow for the 
//further script to run and send mails to user. 
if (strtotime('now') - $cron_time  >= 300){

    //Updating the cron with the changed time i.e, now
    $stmt6  = mysqli_prepare($conn,'UPDATE cron_work SET cron_time = ?');
    $cron_time = strtotime('now'); 
    mysqli_stmt_bind_param($stmt6,'i', $cron_time);
    $working = mysqli_stmt_execute($stmt6);

    //Checking if  the query executed or not
    if($working){

        //body is the variable which will be added to the mail to be sent to the User
        $body = '';

        //Saving the URL given by RTCAMP to be monitored every 5 minutes in the feed variable
        $feed  = 'http://www.github.com/timeline';

        //Using simplexml_load_file() function to convert the xml to object and store in a variable
        $xml = simplexml_load_file($feed);

        //Adding Recent 15 updates to the body variable to attach it to the mail to update the user
        for($i = 0; $i < 15; $i++){
            
            //Extracting the Data from the xml object
            $published = $xml->entry[$i]->published;
            $shortDate = date('m/d/Y', strtotime($published));
            $title = $xml->entry[$i]->title;
            $author = $xml->entry[$i]->author->name;
            $uri = $xml->entry[$i]->author->uri;

            //Appending the Data to the body variable 
            $body .= '<div>';
            $body .= '<h2><a href=';
            $body .= '"';
            $body .= $uri;
            $body .= '">';
            $body .= $title;
            $body .= '</a></h2>';
            $body .= '<h4>Published:';
            $body .=  $shortDate;
            $body .=  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
            $body .= 'By:';
            $body .= $author;
            $body .= '</h4>';
            $body .= '</div>';
            $body .= '<hr>';
}

//Query to select all the Users from the Database
$query = 'SELECT * FROM users';
$result = mysqli_query($conn,$query);


//If there is no Email in the database, don't proceed further 
if(!$result)
exit();

else{
    
    //Collecting all emails into a array
    $emails = mysqli_fetch_all($result,MYSQLI_ASSOC);

    //Running a loop on the emails array and send everyone update according to schedule
    foreach ($emails as $mail):

        //Encrypting the user Email to attach it to the Unsubscribe Email URL
        $encry = encrypt($mail['email']); 
        
        //body1 and $body2 are the variables to help append Heading & Unsubscribe Link         
        $body1 = '';
        $body2 = '';
        $body1 = '<h1>Here are updates from Github Timeline</h1>';
        $body1 .= '<hr>';
        $body1 .= $body;
        $body2 = $body1;

        if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)){
            $protocol = 'https://';
        }
        else{
            $protocol = 'http://';
        }
        if(isset($_SERVER['HTTP_HOST'])){
            $server = $_SERVER['HTTP_HOST'];
        }
        
        //Adding complete address to the protocol
        $protocol .= $server;
        $protocol .= '/';
        $protocol .= basename(dirname(__FILE__));
        $protocol .= '/auth/unsubscribe';

        
        //Appending the Unsubscribing URL
        $body2 .= '<h5>';
        $body2 .= '<a href=';
        $body2 .= '"';
        $body2 .= $protocol;
        $body2 .= '?id=';
        $body2 .= $mail['id'];
        $body2 .= '&token=';
        $body2 .= $encry;
        $body2 .= '">Unsubscribe Here</a>';
        $body2 .= '</h5>';

        //Using Curl to use Sendgrid API to send mails with updated data to subscribed users
        $sendgrid_apikey = API_KEY;
        $url = 'https://api.sendgrid.com/';
        $pass = $sendgrid_apikey;
        
        $params = array(
            'to'        => $mail['email'],
            'from'      => from_mail,
            'fromname'  => from_name,
            'subject'   => 'Github Timeline Updates',
            'html'      => $body2,
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
    endforeach;
}
}
else{
    exit('Error adding the cron_time in the database!');
}
}
else{
    exit('Cooling Period on!! Wait 5 mins;)');
}
?>




