<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Setup database connection

mysql_connect("MYSQLHOST", "MYSQLLOGIN", "MYSQLPASS") or die(mysql_error());
mysql_select_db("MYSQLDB") or die(mysql_error());



$request_url = "https://SOCKETLABSLOGIN:SOCKETLABSAPIKEY@api.email-od.com/messagesFblReported?accountId=2042&disposition=0&type=json&startDate=".date('Y-m-d'); //returns feed like above

$json = file_get_contents($request_url, true); //getting the file content

$decode = json_decode($json, true);  // create JSON in array


foreach($decode['collection'] as $spam_complaint)
{
	$spam_email = $spam_complaint['OriginalRecipient']; // grab email address
	
	
	
	$result = mysql_query("SELECT * FROM civicrm_email where email = '".$spam_email."'")
	or die(mysql_error());  
	$row = mysql_fetch_array( $result );

	$contactid = $row['contact_id'];
	
	echo "Email: ".$spam_email." <br>";
	echo "ContactID: ".$contactid."<br><br>";
	
	$blacklist = mysql_query("UPDATE civicrm_contact SET do_not_email=1 WHERE id='".$contactid."'") 
	or die(mysql_error());  
		
}


?>

