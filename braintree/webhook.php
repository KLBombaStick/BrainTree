<?php
require_once "lib/Braintree.php";

//[TRACK ACCESS Logs] 
$log_dt = "";

//echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
print_r(get_headers("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", 1));

foreach (getallheaders() as $name => $value) {
    echo "$name: $value\n";
}

foreach($_REQUEST as $key => $value){
    $log_dt .= $key."=".$value."|";
}

$log = date("Y-m-d H:i:s")."|LOG|".$log_dt."\r\n";
file_put_contents("./access.log", $log, FILE_APPEND); 

$notification = Braintree_WebhookNotification::parse(
	$_POST['bt_signature'],
	$_POST['bt_payload']
);

$log2 = date("Y-m-d H:i:s");

if($notification->kind == Braintree_WebhookNotification::SUB_MERCHANT_ACCOUNT_APPROVED){
	
	// true
	$log2 .= "|Status:".$notification->merchantAccount->status;
	// "active"
	$log2 .= "|ID:".$notification->merchantAccount->id;
	// "blue_ladders_store"
	$log2 .= "|MasterAccID:".$notification->merchantAccount->masterMerchantAccount->id;
	// "14ladders_marketplace"
	$log2 .= "|MasterAccStatus:".$notification->merchantAccount->masterMerchantAccount->status;
	// "active"
	
	
}else{
	//$notification->kind == Braintree_WebhookNotification::SUB_MERCHANT_ACCOUNT_DECLINED;
	
	// true
	$log2 .= "|Message:".$notification->message;
	// "Credit score is too low"
	$log2 .= "|Error:".$notification->errors;
	// Braintree_Error_ValidationErrorCollection Object ( . . . )
	
}

$log2 .= "\r\n";

file_put_contents("./webhook.log", $log2, FILE_APPEND); 



?>