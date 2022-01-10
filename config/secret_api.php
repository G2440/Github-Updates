<?php
require_once dirname(__FILE__) . '/secrets.php';

// Secret key from SendGrid account
// from_mail & from_name used for sender verification at Sendgrid

define('API_KEY', $key);
define('from_mail', $from);
define('from_name', $name);

?>