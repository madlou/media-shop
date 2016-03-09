<?php

 include("includes/connect.php");
 
 // read the post from PayPal system and add 'cmd'
 $req = 'cmd=_notify-validate';
 foreach ($_POST as $key => $value) {
  $value = urlencode(stripslashes($value));
  $req .= "&$key=$value";
 }

 // post back to PayPal system to validate
 $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
 $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
 $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
 $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

 // assign posted variables to local variables
 $item_name = $_POST['item_name'];
 $item_number = $_POST['item_number'];
 $payment_status = $_POST['payment_status'];
 $payment_amount = $_POST['mc_gross'];
 $payment_currency = $_POST['mc_currency'];
 $txn_id = $_POST['txn_id'];
 $receiver_email = $_POST['receiver_email'];
 $payer_email = $_POST['payer_email'];
 if (!$fp) {
  // HTTP ERROR
  $insert = mysql_query("INSERT INTO `paypal` VALUES ('', 'HTTP Error', '$item_name', '$payment_status', '$payment_amount', '$payment_currency', '$txn_id', '$receiver_email', '$payer_email')");
  $emailmessage = "Dear Customer,\n Thankyou for your order.\n\nThere seems to be a technical issue with our server.\n\nPlease be patient, we will contact you shortly.";
  mail($payer_email, "Your Order...", $emailmessage, "From: ".$receiver_email."\nReply-To: ".$receiver_email."");
  mail($receiver_email, "Error in order txn_id: ".$txn_id, "This has been sent to the customer:\n\n".$emailmessage."\n\n<b>Refer to the admin database for more details.</b>", "From: ".$receiver_email."\nReply-To: ".$receiver_email."");
 } else if($payment_status=="Completed") {
  fputs ($fp, $header . $req);
  while (!feof($fp)) {
   $res = fgets ($fp, 1024);
   if (strcmp ($res, "VERIFIED") == 0) {
    // check the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your Primary PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment
    $data_user_name=split(":",$item_name);
    $insert = mysql_query("INSERT INTO `paypal` VALUES ('', 'Verified', '$item_name', '$payment_status', '$payment_amount', '$payment_currency', '$txn_id', '$receiver_email', '$payer_email')");
    $select = mysql_query("SELECT * FROM `users` WHERE `name` LIKE '".$data_user_name[0]."';");
    $row = mysql_fetch_array($select);
    if($row["name"]){
     $select2 = mysql_query("SELECT m.price, o.number FROM orders o, media m WHERE o.code = m.number AND o.status = 'basket' AND o.user = '".$row["name"]."';");
     $total=0;
     $items="Media codes paid for: ";
     while($check=mysql_fetch_array($select2)){
      $total=$total+$check[0];
      if($total<=$payment_amount){
       $items.=$check[1].", ";
       $update = mysql_query("UPDATE `orders` SET `status` = 'paid' WHERE `number` = ".$check[1].";");
      } else {
       $items.="(".$check[1]." not added), ";
      }
     }
     $items=substr($items,0,strlen($items)-2);
     $emailmessage = "Dear Customer,\n Thankyou for your order.\n\nPlease log into our site to access your media.\n\nFrom the Homepage, login and then select 'View Purchases'.\n\n".$items;
     mail($payer_email, "Your Order...", $emailmessage, "From: ".$receiver_email."\nReply-To: ".$receiver_email."");
     mail($receiver_email, "Successful order ~ txn_id: ".$txn_id, "This has been sent to the customer:\n\n".$emailmessage."\n\n<b>Refer to the admin database for more details.</b>", "From: ".$receiver_email."\nReply-To: ".$receiver_email."");
    } else {
//"Test bits: item name '$item_name',item number '$item_number', payment status '$payment_status', payment amount'$payment_amount', payment currency '$payment_currency', txn id '$txn_id', receiver email '$receiver_email', payer email '$payer_email'  \n\n\n\n
     $emailmessage = "Dear Customer,\n Thankyou for your order.\n\nWe were unable to find Your details on our database.\n\nPlease be patient, we will contact you shortly.";
     mail($payer_email, "Your Order...", $emailmessage, "From: ".$receiver_email."\nReply-To: ".$receiver_email."");
     mail($receiver_email, "Manual update needed on order txn_id: ".$txn_id." ~ Customers email: ".$payer_email, "This has been sent to the customer:\n\n".$emailmessage."\n\nRefer to the admin database for more details.", "From: ".$receiver_email."\nReply-To: ".$receiver_email."");
    }
   } else if (strcmp ($res, "INVALID") == 0) {
    // log for manual investigation
  	$insert = mysql_query("INSERT INTO `paypal` VALUES ('', 'Invalid', '$item_name', '$payment_status', '$payment_amount', '$payment_currency', '$txn_id', '$receiver_email', '$payer_email')");
    $emailmessage = "Dear Customer,\n Thankyou for your order.\n\nThere seems to be an issue with the paypal details you supplied, we are looking into it now.\n\nPlease be patient, we will contact you shortly.";
    mail($payer_email, "Your Order...", $emailmessage, "From: ".$receiver_email."\nReply-To: ".$receiver_email."");
    mail($receiver_email, "Error in order txn_id: ".$txn_id, "This has been sent to the customer:\n\n".$emailmessage."\n\nRefer to the admin database for more details.", "From: ".$receiver_email."\nReply-To: ".$receiver_email."");
   }
  }
  fclose ($fp);
 }

?>
