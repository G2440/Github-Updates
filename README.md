# Github Updates

### Description
- **[Github Updates](https://d630-54-151-179-156.ngrok.io/)** is a service which provides a user with updates from the **github timeline** every 5 minutes.
- **[Github Updates](https://d630-54-151-179-156.ngrok.io/)** asks for the **email** of the user and once the user verifies their email, they start to get updates from github timeline every five minutes and all those mails contain an Unsubscribe Link to turn off the service.

### LIVE LINK FOR THE APP : [https://d630-54-151-179-156.ngrok.io/](https://d630-54-151-179-156.ngrok.io/)


### Functioning of the Application 

- User logs on to the **[Home Page](https://d630-54-151-179-156.ngrok.io/)** and enters their Email to get the updates.
- The Email input by the user will then be checked for **validation** and if correct it is then **sanitized** to prevent SQL injection.
- Then a **token** will be generated with the help of Email ID and attached to a mail and sent to User for **Verification**.
- Once the User receives the confirmation mail , they will click on the **Confirm Mail** URL and a request will be sent to the server in which **token**
  and Email of the User will be sent and server will **verify** the **token** for the respective User and User will be **added** to the **database** if not already present i.e, they will be **subscribed**.   
- Once the User is subscribed they will be **redirected** to **Successful Subscription** Page and Every **five minutes** a mail with updated monitored 
  data from **Github Timeline** will be sent to the User.
- For sending update mail every five minutes, a **cron job** has been setup on **[Cron-Job](https://cron-job.org/en/)** for the **Live Application** and
  for running on **local machine** , a  **time-check script** has been attached to the **[sendupdates](https://github.com/rtlearn/php-gn24/blob/master/sendupdates.php)** file for ensuring that script runs only after 5 minutes.  
- An **unsubscribing link** has also been added in every mail that is sent to the User with Updated data. Once the User clicks on the link, the **id**
  along with the **token** will be sent back to the server and if the User **verifies** and present in the database, User will be **Unsubscribed**.     
<br/>

### NOTE

- If you don't receive the mail then do check the **spam** folder of your Email. Sometimes the security options don't allow frequent mails and send it to 
  spam. If you are looking to test this application, its better to use **[Temp Mail](https://temp-mail.org/en/)**. 
<br/>

### Directory Structure

- `assets`
  - **[assets directory](https://github.com/rtlearn/php-gn24/tree/master/assets)** contains three directories **css, helper, snaps**.
    - **[css](https://github.com/rtlearn/php-gn24/tree/master/assets/css)** directory contains the files needed for styling the web pages.
    - **[helper](https://github.com/rtlearn/php-gn24/tree/master/assets/helper)** directory contains the helper functions needed by the application.
    - **[snaps](https://github.com/rtlearn/php-gn24/tree/master/assets/snaps)** directory contains the images and video prototype used in the project.
  
- `auth`
  - **[subscribe.php](https://github.com/rtlearn/php-gn24/blob/master/auth/subscribe.php)** is used to verify and add the user email in the database.
  - **[unsubscribe.php](https://github.com/rtlearn/php-gn24/blob/master/auth/unsubscribe.php)** is used to verify the user and stop sending updates to them.
  
- `configs`
  
  - **[db.php](https://github.com/rtlearn/php-gn24/blob/master/config/db.php)** contains the MYSQL database configuration and used to connect to the  database.
  - **[secret_api](https://github.com/rtlearn/php-gn24/blob/master/config/secret_api.php)** contains the sendgrid api key and other configurations.
   
<br/>

# DATABASE SNAPSHOT

  -![DATABASE](/assets/snaps/db.PNG)
  
  -![CRONDB](/assets/snaps/cron1.PNG)
 
# CRON-JOB SNAPSHOT

  -![CRON-JOB](/assets/snaps/cron.PNG)  

<br/>

# APPLICATION SNAPSHOTS

  <br/>

  -![HOMEPAGE](/assets/snaps/home.PNG)
  
<h3 align="center">HOMEPAGE OF THE APPLICATION</h3>
  
<br/>

  -![INPUT](/assets/snaps/input.PNG)
<h3 align="center">USER ENTERS THE E-MAIL FOR REGISTRATION</h3>
 
<br/>
  
  -![Confirm Mail](/assets/snaps/confirmmail.PNG)
<h3 align="center">MAIL TO THE USER FOR VERIFICATION</h3>

<br/>
  
  -![User Subscribing](/assets/snaps/subbing.PNG)
<h3 align="center">USER SUBSCRIBING SCENARIOS</h3>
<br/>
  
  -![SUBSCRIBED](/assets/snaps/subbed.PNG)
<h3 align="center">ON SUCCESSFUL SUBSCRIPTION</h3>
<br/>
  
  -![Updates Mail](/assets/snaps/maildata.PNG)
<h3 align="center">MAIL WITH UPDATES TO THE USER</h3>
<br/>
 
 -![User Unsubscribing](/assets/snaps/unsub.PNG)
<h3 align="center">USER UNSUBSCRIBING SCENARIOS</h3>
<br/>

### Requirements
- **[XAMPP](https://www.apachefriends.org/index.html)**
- **[SendGrid Account](https://sendgrid.com/)**

### Process to run on Local Machine
  - Move the downloaded folder of **php-gn24** to **C:\xampp\htdocs**.
  - Open [XAMPP](https://www.apachefriends.org/index.html) and start **Apache Server** and **MySql**.
  - Click on the **Admin** to open php-MyAdmin.
  - Create a table in php-MyAdmin using **schema** provided.
  - Create a file in **[config](https://github.com/rtlearn/php-gn24/blob/master/config)** directory with **database** & **API** credentials named **secrets.php** .
  - Add Sendgrid Twilio Web API **library** to the php-gn24 directory.
  - Create an account on [SendGrid](https://sendgrid.com/) and create a **secret api key** for the API.
  - Attach Cron to **[sendupdates.php](https://github.com/rtlearn/php-gn24/blob/master/sendupdates.php)** for running updates every 5 minutes.
  - Change the configuration of the **database** and **sendgrid account** in the **[config](https://github.com/rtlearn/php-gn24/blob/master/config)** directory.
  - Open the **your-host** on browser and open the **php-gn24** folder.
