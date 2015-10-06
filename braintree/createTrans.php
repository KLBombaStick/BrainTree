<?php
error_reporting(0); ini_set('display_errors', 0);

require_once 'lib/Braintree.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('5xwqxqd7pkqwxfjs');
Braintree_Configuration::publicKey('8pfkt34wzyvrnnwy');
Braintree_Configuration::privateKey('8b63a0ae59085a11912527848633b848');


echo "<pre>";
if(isset($_POST["payment_method_nonce"]) && !isset($_POST["_act"])){
	$nonce = $_POST["payment_method_nonce"];
	/* Use payment method nonce here */
	$storeInVaultOnSuccess = $_POST['SIV'] != 1 ? 'false' : 'true';
	$submitForSettlement = $_POST['SFS']  != 1 ? 'false' : 'true';
	
	$result = Braintree_Transaction::sale(array(
		'amount' => '100.00',
		'paymentMethodNonce' => $nonce,
		'options' => array(
					'submitForSettlement' => $submitForSettlement,
					'storeInVaultOnSuccess' => $storeInVaultOnSuccess //[TO RETURN CUSTOMER ID]
		)	
	));
	
	echo "<div style='height:300px; overflow-y:scroll; background-color:#fff;'> <h3>API response</h3>";
		//print_r($result);
		echo json_encode($result, JSON_PRETTY_PRINT);
	//print_r($result -> transaction -> _attributes['id']);
	echo "</div>";
	
	echo "<form method='POST' action='createTrans.php'><h3>Proceed to settlement for ".$result -> transaction -> _attributes['id']."</h3></br>";
	//echo "<a href='?_act=settlement&id=".$result -> transaction -> _attributes['id']."'>Do the settlement</a>";
		echo '<input type="hidden" name="_act" value="settlement" />';
		echo '</br>Transaction ID: <input type="text" name="id" value="'.$result -> transaction -> _attributes['id'].'" />';
		echo '</br>Specific Amount to settle: <input type="text" name="amt" value="" />';
		echo '</br><input type="submit" name="" value="Proceed to settlement " />';
	echo "</form>";
	echo "--------------------------------------------------------------------------------------------------------------------------";
	
	echo "</br></br><form method='POST' action='createTrans.php'><h3>Proceed to void for ".$result -> transaction -> _attributes['id']."</h3></br>";
	//echo "<a href='?_act=settlement&id=".$result -> transaction -> _attributes['id']."'>Do the settlement</a>";
		echo '<input type="hidden" name="_act" value="void" />';
		echo '</br>Transaction ID: <input type="text" name="id" value="'.$result -> transaction -> _attributes['id'].'" />';
		echo '</br><input type="submit" name="" value="Proceed to void " />';
	echo "</form>";
	echo "--------------------------------------------------------------------------------------------------------------------------";
	
	echo "</br></br><form method='POST' action='createTrans.php'><h3>Proceed to find ".$result -> transaction -> _attributes['id']."</h3></br>";
	//echo "<a href='?_act=settlement&id=".$result -> transaction -> _attributes['id']."'>Do the settlement</a>";
		echo '<input type="hidden" name="_act" value="find" />';
		echo '</br>Transaction ID: <input type="text" name="id" value="'.$result -> transaction -> _attributes['id'].'" />';
		echo '</br><input type="submit" name="" value="Proceed to Find ID " />';
	echo "</form>";
	echo "--------------------------------------------------------------------------------------------------------------------------";
	
	echo "</br></br><form method='POST' action='createTrans.php'><h3>Proceed to clone for ".$result -> transaction -> _attributes['id']."</h3></br>";
	//echo "<a href='?_act=settlement&id=".$result -> transaction -> _attributes['id']."'>Do the settlement</a>";
		echo '<input type="hidden" name="_act" value="clone" />';
		echo '</br>Transaction ID: <input type="text" name="id" value="'.$result -> transaction -> _attributes['id'].'" />';
		echo '</br><input type="submit" name="" value="Proceed to clone transaction " />';
	echo "</form>";
	echo "--------------------------------------------------------------------------------------------------------------------------";
	
	
	exit();
}else if($_POST['_act'] == 'settlement'){

	$result = Braintree_Transaction::submitForSettlement($_POST['id'], $_POST['amt']);
	
	echo "<div style='height:300px; overflow-y:scroll; background-color:#fff;'> <h3>API response</h3>";
		echo json_encode($result, JSON_PRETTY_PRINT);
	echo "</div>";
}else if($_POST['_act'] == 'void'){

	$result = Braintree_Transaction::void($_POST['id']);
	
	print_r($result);
}else if($_REQUEST['_act'] == 'refund'){
	echo "</br></br><form method='POST' action=''><h2>Proceed to refund</h2></br>";
		echo '<input type="hidden" name="_act" value="refund_process" />';
		echo '</br>Transaction ID: <input type="text" name="id" value="" />';
		echo '</br>Amount: <input type="text" name="amt" value="" />';
		echo '</br><input type="submit" name="" value="Proceed to Refund " />';
	echo "</form>";
	echo "--------------------------------------------------------------------------------------------------------------------------";
	
}else if($_REQUEST['_act'] == 'refund_process'){
	$result = Braintree_Transaction::refund((string)$_POST['id'], (string)$_POST['amt']);
	
	
	echo "<div style='height:300px; overflow-y:scroll; background-color:#fff;'> <h3>API response</h3>";
		echo json_encode($result, JSON_PRETTY_PRINT);
	echo "</div>";
	
	exit();
}else if($_POST['_act'] == 'find'){
	$result = Braintree_Transaction::find($_POST['id']);
	
	print_r($result);
}else if($_POST['_act'] == 'clone'){
	$result = Braintree_Transaction::cloneTransaction($_POST['id'], array(
		'amount' => '10.00',
		'options' => array(
			'submitForSettlement' => true
		)
	));

	print_r($result);
}
//[Create Customer & Create Payment Method]
else if($_POST['_act'] == 'createCustomer'){
	if(isset($_POST['data'])){
		$postData = explode("&", urldecode ($_POST['data']));
		
		//[SETTING UP POST DATA TO SUPPORT AJAX]
		foreach($postData as $key => $value){
			$postValue = explode("=", $value);
			
			$_POST[$postValue[0]] = $postValue[1];
		}
		unset($_POST['data']);
	}
	
	$result = Braintree_Customer::create(array(
		'firstName' => $_POST['firstName'],
		'lastName' => $_POST['lastName'],
		'company' => $_POST['company'],
		'email' => $_POST['email'],
		'phone' => $_POST['phone'],
		'fax' => $_POST['fax'],
		'website' => $_POST['website'],
		'customFields' => array(
			'custom_field_one' => $_POST['custom1'],
			'custom_field_two' => $_POST['custom2']
		),
		'paymentMethodNonce' => $_POST["payment_method_nonce"]
	));

	$result->success;	# true
	echo "</br>";
	
	//[ADD CUSTOMER ID INTO LOG]
	$result->customer->id;	# Generated customer id
	$file = './data/customerID.txt';	//[ADD Customer ID to text file]
	$customer = $result->customer->id."\r\n";
	// Write the contents to the file, 
	// using the FILE_APPEND flag to append the content to the end of the file
	// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
	file_put_contents($file, $customer, FILE_APPEND | LOCK_EX);
	
	echo "</br>";
	
	
	echo "<div> <h3>API response</h3>";
	//echo json_encode($result, JSON_PRETTY_PRINT);
	//echo json_encode($result);
		echo "<pre>";
		print_r($result);
	echo "</div>";
	
	
}else if($_POST['_act'] == 'updateCustomer'){
	
	$updateResult = Braintree_Customer::update(
    $_POST['customer_id'],
		array(
		'firstName' => $_POST['firstName'],
		'lastName' => $_POST['lastName'],
		'company' => $_POST['company'],
		'email' => $_POST['email'],
		'phone' => $_POST['phone'],
		'fax' => $_POST['fax'],
		'website' => $_POST['website'],
	  )
	);

	$updateResult->success;
	
	print_r($updateResult);
}else if($_POST['_act'] == 'findCustomer'){
	//echo $_POST['customer_id'];
	//$_POST['customer_id'] = '57306224';
	$findResult = Braintree_Customer::find((string)$_POST['customer_id']);
	
	print_r($findResult);
}else if($_POST['_act'] == 'deleteCustomer'){
	$search = (string)$_POST['customer_id'];
	$file = './data/customerID.txt';
	$contents = file_get_contents($file);
	echo $contents = str_replace($_POST['customer_id'], trim((string)$_POST['customer_id']."_DELETED\r\n"), $contents);
	file_put_contents($file,$contents);
	
	
	$deleteResult = Braintree_Customer::delete((string)$_POST['customer_id']);

	
	print_r($deleteResult);
}else if($_POST['_act'] == 'createPaymentMethod'){
	$para_Arr = array();
	
	if(isset($_POST['data'])){
		$postData = explode("&", urldecode ($_POST['data']));
		
		
		//[SETTING UP POST DATA TO SUPPORT AJAX]
		foreach($postData as $key => $value){
				$postValue = explode("=", $value);
				
				$_POST[$postValue[0]] = $postValue[1];
		}
		unset($_POST['data']);
	}
	
	if($_POST['verify'] == "true"){
		echo "With Verify </br>";	
		$para_Arr = array(
			'customerId' => $_POST['customer_id'],
			'paymentMethodNonce' => $_POST["payment_method_nonce"],
			'options' => array(
				'verifyCard' => true,
			//	'verificationMerchantAccountId' => '5xwqxqd7pkqwxfjs',
			)

		);
	}else{
		echo "Without Verify </br>";
		$para_Arr = array(
			'customerId' => $_POST['customer_id'],
			'paymentMethodNonce' => $_POST["payment_method_nonce"]
		);
	}
	 	
	$result = Braintree_PaymentMethod::create($para_Arr);
	
	$file = './data/token.txt';	//[ADD Customer ID to text file]
	$token = $result->paymentMethod->token."\r\n";
	// Write the contents to the file, 
	// using the FILE_APPEND flag to append the content to the end of the file
	// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
	file_put_contents($file, $token, FILE_APPEND | LOCK_EX);
	
	//print_r($result);
	
	echo "<div style='height:300px; overflow-y:scroll; background-color:#fff;'> <h3>API response</h3>";
		echo json_encode($result, JSON_PRETTY_PRINT);
	echo "</div>";
	
}elseif($_REQUEST['_act'] == 'payByToken'){
	if(isset($_POST['data'])){
		$postData = explode("&", urldecode ($_POST['data']));
		
		
		//[SETTING UP POST DATA TO SUPPORT AJAX]
		foreach($postData as $key => $value){
				$postValue = explode("=", $value);
				
				$_POST[$postValue[0]] = $postValue[1];
		}
		unset($_POST['data']);
	}
	
	$result = Braintree_Transaction::sale(array(
		'amount' => $_POST['amt'],
		'paymentMethodToken' => $_POST['token'],
	));
	
	echo "<div style='height:300px; overflow-y:scroll; background-color:#fff;'> <h3>API response</h3>";
		echo json_encode($result, JSON_PRETTY_PRINT);
	echo "</div>";

	
}else if($_POST['_act'] == 'updatePaymentMethod'){
	if(isset($_POST['billingAddressId']) & $_POST['billingAddressId'] != ""){
		$updateResult = Braintree_PaymentMethod::update(
			$_POST['token'],
			array(
				'billingAddressId' => $_POST['billingAddressId']
			)
		);
	}else{
		$updateResult = Braintree_PaymentMethod::update(
		  $_POST['token'],
		  array(
			'billingAddress' => array(
				'streetAddress' => $_POST['streetAddress'],
				'options' => array(
					'updateExisting' => $_POST['updateExisting'],
				//	'makeDefault' => true
				)
			)
		  )
		);
	}
	
	print_r($updateResult);
}else if($_POST['_act'] == 'findPaymentMethod'){
	
	$paymentMethod = Braintree_PaymentMethod::find($_POST['token']);
	
	print_r($paymentMethod);
}else if($_POST['_act'] == 'deletePaymentMethod'){
	$search = (string)$_POST['token'];
	$file = './data/token.txt';
	$contents = file_get_contents($file);
	echo $contents = str_replace($_POST['token'], trim((string)$_POST['token']."_DELETED\r\n"), $contents);
	file_put_contents($file,$contents);
	
	$paymentMethod = Braintree_PaymentMethod::delete($_POST['token']);
	
	print_r($paymentMethod);
}else if($_POST['_act'] == 'createSubMerchant'){
	$result = Braintree_MerchantAccount::create(
		  array(
			'individual' => array(
				'firstName' => $_POST['firstName'],
				'lastName' => $_POST['lastName'],
				'email' => $_POST['email'],
				'phone' => $_POST['phone'],
				'dateOfBirth' => $_POST['dateOfBirth'],
				'ssn' => $_POST['ssn'],
				'address' => array(
					'streetAddress' => $_POST['streetAddress'],
					'locality' => $_POST['locality'],
					'region' => $_POST['region'],
					'postalCode' => $_POST['postalCode'],
			  )
			),
			'business' => array(
			  'legalName' => $_POST['legalName'],
			  'dbaName' => $_POST['dbaName'],
			  'taxId' => $_POST['taxId'],
			  'address' => array(
					'streetAddress' => $_POST['streetAddress2'],
					'locality' => $_POST['locality2'],
					'region' => $_POST['region2'],
					'postalCode' => $_POST['postalCode2'],
			  )
			),
			'funding' => array(
			  'descriptor' => $_POST['descriptor'],
			  'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
			  'email' =>  $_POST['email'],
			  'mobilePhone' =>  $_POST['mobilePhone'],
			  'accountNumber' =>  $_POST['accountNumber'],
			  'routingNumber' =>  $_POST['routingNumber'],
			),
			'tosAccepted' => true,
			'masterMerchantAccountId' => "qzmnkckz54r94r4f",
			'id' => "mc_".time()
		)
	);
	
	$file = './data/subMerchant.txt';	//[ADD Customer ID to text file]
	$subMerchantID = $result->merchantAccount->id."\r\n";
	// Write the contents to the file, 
	// using the FILE_APPEND flag to append the content to the end of the file
	// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
	file_put_contents($file, $subMerchantID, FILE_APPEND | LOCK_EX);

	print_r($result);

}else if($_POST['_act'] == 'CreateTransEscrow'){
	
	$result = Braintree_Transaction::sale(array(
		'merchantAccountId' => $_POST["sub_merchant_id"],
		'amount' => $_POST["amount"],
		'paymentMethodNonce' => $_POST["payment_method_nonce"],
		'serviceFeeAmount' => $_POST["serviceFeeAmount"],
		'options' => array(
		//	'submitForSettlement' => true,
			'holdInEscrow' => true,
		),
	));
	
	print_r($result);
	
	$file = './data/trans_id.txt';	//[ADD Customer ID to text file]
	$transID = $result->transaction->id."\r\n";
	// Write the contents to the file, 
	// using the FILE_APPEND flag to append the content to the end of the file
	// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
	file_put_contents($file, $transID, FILE_APPEND | LOCK_EX);

	print_r($result);
	
	
	
}else if($_POST['_act'] == 'ReleaseTransEscrow'){
	
	$result = Braintree_Transaction::releaseFromEscrow($_POST['trans_id']);
	
	print_r($result);
}



?>

