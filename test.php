<?php
echo 'test';
// this line loads the library 
require('/twilio-php-master/Services/Twilio.php'); 

$account_sid = 'ACae2c02b20dd4109cd30d1b0205c94ed0'; 
$auth_token = '2fcf1dce0a0a37e2329542421a8683dc'; 
$client = new Services_Twilio($account_sid, $auth_token); 
 
$client->account->messages->create(array(  
	'To' => "+3153807451", 
	'From' => "+18445151996", 
	'Body' => "test body message",     
));

print_r($client);

?>
test
