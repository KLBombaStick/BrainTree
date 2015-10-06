<?php
/*
pp_michael
*/
require_once 'lib/Braintree.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('5xwqxqd7pkqwxfjs');
Braintree_Configuration::publicKey('8pfkt34wzyvrnnwy');
Braintree_Configuration::privateKey('8b63a0ae59085a11912527848633b848');

$clientToken = Braintree_ClientToken::generate();



if(isset($_GET['act'])){
	$data = array(
		"client_token" => $clientToken,
	);
	
	echo json_encode($data);
	exit();
}


?>
<style>
	h2{
		font-size:15px;
		margin:0px;
		padding:0px;
	}
</style>
<div style="width:450px;">
<form method="post" action="createTrans.php" id="myform">
	<div>Create Sub Merchant:</div>
		<input type="hidden" name="_act" value="createSubMerchant" />
		</br>
		<h2>Customer Info</h2>
		<div>First Name: </div><input type="text" name="firstName" value="Mike" />
		</br><div>Last Name: </div><input type="text" name="lastName" value="Jones" />
		</br><div>Email: </div><input type="text" name="email" value="mike.jones@example.com" />
		</br><div>Phone: </div><input type="text" name="phone" value="281.330.8004" />
		</br><div>dateOfBirth: </div><input type="text" name="dateOfBirth" value="1981-11-19" />
		</br><div>ssn: </div><input type="text" name="ssn" value="456-45-4567" />
		</br><div>streetAddress: </div><input type="text" name="streetAddress" value="111 Main St" />
		</br><div>locality: </div><input type="text" name="locality" value="Chicago" />
		</br><div>region: </div><input type="text" name="region" value="IL" />
		</br><div>postalCode: </div><input type="text" name="postalCode" value="60622" />
		</br>
		</br>
		<h2>Business Info</h2>
		<div>legalName: </div><input type="text" name="legalName" value="Jennifer's Lopez" />
		</br><div>dbaName: </div><input type="text" name="dbaName" value="Jennifer's Lopez" />
		</br><div>taxId: </div><input type="text" name="taxId" value="98-7654321" />
		
		</br><div>streetAddress: </div><input type="text" name="streetAddress2" value="111 Main St" />
		</br><div>locality: </div><input type="text" name="locality2" value="Chicago" />
		</br><div>region: </div><input type="text" name="region2" value="IL" />
		</br><div>postalCode: </div><input type="text" name="postalCode2" value="60622" />
		</br>
		</br>
		<h2>Funding Info</h2>
		<div>descriptor: </div><input type="text" name="descriptor" value="Blue Ladders Maybank" />
		</br><div>email: </div><input type="text" name="email" value="funding@blueladders.com" />
		</br><div>mobilePhone: </div><input type="text" name="mobilePhone" value="5555555555" />
		</br><div>accountNumber: </div><input type="text" name="accountNumber" value="1123581321" />
		</br><div>routingNumber: </div><input type="text" name="routingNumber" value="071101307" />
		
		
		<br/>
		<br/>
		<input type="submit" value="Create SubMerchant" onclick="submitForm()">
</form>
</div>

<div>
<h3>Create Payment</h3>
<form method="post" action="createTrans.php" >

<input type="hidden" name="_act" value="CreateTransEscrow" />

<div>Sub Merchant ID:</div>
<select name="sub_merchant_id">
<?php
	$data = file_get_contents('./data/subMerchant.txt', true);
	
	$subM_Arr = explode("\n", trim($data));	
	
	foreach($subM_Arr as $value){
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>
</select>
</br><div>Amount: </div><input type="text" name="amount" value="100" />
</br><div>Service Fees: </div><input type="text" name="serviceFeeAmount" value="10" />
	</br></br></br>
	<div id="smcPayment"></div>
	</br></br></br>
	<input type="submit" value="Create Payment">
</form>

</div>

<div style="width:450px;">
<h3>Release escrow</h3>
<form method="post" action="createTrans.php" >

<input type="hidden" name="_act" value="ReleaseTransEscrow" />
<select name="trans_id">
<?php
	$data = file_get_contents('./data/trans_id.txt', true);
	
	$transId_Arr = explode("\n", trim($data));	
	
	foreach($transId_Arr as $value){
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>
</select>
	</br></br></br>
	<input type="submit" value="Release Escrow">
</form>

</div>

<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script>
var clientToken = '<?= $clientToken ?>';

braintree.setup(clientToken,'dropin', {
   container: 'smcPayment', 
   singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
});
</script>
