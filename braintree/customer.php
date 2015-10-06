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
	div{
		width:200px;
		float:left;
	}

</style>
<!--
<div style="width:400px;">
	<h3>Create Customer</h3>
    <form method="post" action="createTrans.php" id="myform">
		
		<div>Store Payment Method:</div>
		<select name="store" id="storedPayment" onchange="dropChange()">
			<option value="true">Yes, do it</option>
			<option value="false">No, don't</option>
		</select></br>
		<input type="hidden" name="_act" value="createCustomer" />
		
		</br><div>First Name: </div><input type="text" name="firstName" value="Mike" />
		</br><div>Last Name: </div><input type="text" name="lastName" value="Jones" />
		</br><div>Company: </div><input type="text" name="company" value="Jones Co." />
		</br><div>Email: </div><input type="text" name="email" value="mike.jones@example.com" />
		</br><div>Phone: </div><input type="text" name="phone" value="281.330.8004" />
		</br><div>Fax: </div><input type="text" name="fax" value="419.555.1235" />
		</br><div>Website: </div><input type="text" name="website" value="http://example.com" />
		</br><div>custom1: </div><input type="text" name="custom1" value="Your Custom Value 1" />
		</br><div>custom2: </div><input type="text" name="custom2" value="Your Custom Value 2" />
		</br></br>
			
        <div id="dropin" style="width:400px; float:none;"></div>
        <br/>
        <br/>
        <input type="submit" value="Create Customer" onclick="submitForm()">
    </form>
</div>
-->

<div style="width:1000px;">
<div style="width:400px;">
<h3>Update Customer</h3>

<form method="post" action="createTrans.php">
<div>CustomerID:</div><select name="customer_id" id="customerID">
<?php
	$data = file_get_contents('./data/customerID.txt', true);
	
	$customerID_Arr = explode("\n", trim($data));	
	
	foreach($customerID_Arr as $value){
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
	print_r($convert);
?>
</select></br>
		<input type="hidden" name="_act" value="updateCustomer" />
		</br><div>First Name: </div><input type="text" name="firstName" value="Mike 2" />
		</br><div>Last Name: </div><input type="text" name="lastName" value="Jones 2" />
		</br><div>Company: </div><input type="text" name="company" value="Jones Co. 2" />
		</br><div>Email: </div><input type="text" name="email" value="mike.jones@example.com" />
		</br><div>Phone: </div><input type="text" name="phone" value="281.330.8004222" />
		</br><div>Fax: </div><input type="text" name="fax" value="419.555.123522" />
		</br><div>Website: </div><input type="text" name="website" value="http://example.com" />
		</br></br>
			
			
        <input type="submit" value="Update Customer">
    </form>

</div>


<div style="width:400px;">
<h3>Find or Delete Customer</h3>

<form method="post" action="createTrans.php">
<div>CustomerID:</div>
<select name="customer_id" id="customerID">
<?php
	$data = file_get_contents('./data/customerID.txt', true);
	
	$customerID_Arr = explode("\n", trim($data));	
	
	foreach($customerID_Arr as $value){
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>
</select>
</br>
</br>
<div>Action:&nbsp;</div>
		<select name="_act" id="actionType" onchange="dropChange2()">
			<option value="findCustomer">Find Customer</option>
			<option value="deleteCustomer">Delete Customer</option>
		</select></br>
		
		</br></br>
        <input type="submit" id="bttn1" value="Find Customer">
    </form>

</div>

<!--
<div style="width:400px;">
<h3>Create Payment Method</h3>

<form method="post" action="createTrans.php">
<div>CustomerID:</div>
<select name="customer_id" id="customerID">
<?php
	$data = file_get_contents('./data/customerID.txt', true);
	
	$customerID_Arr = explode("\n", trim($data));	
	
	foreach($customerID_Arr as $value){
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>
</select>
</br>
</br>
<div>Verify:&nbsp;</div>
		<select name="verify" id="actionType">
			<option value="true">YES</option>
			<option value="false">No</option>
		</select></br>
		<input type="hidden" name="_act" value="createPaymentMethod" />
		</br></br>
		
		<div id="dropin2" style="width:400px; float:none;"></div>
        <input type="submit" id="bttn1" value="Create Payment Method">
    </form>

</div>
-->

<div style="width:400px;">
<h3>Update Payment Method</h3>

<form method="post" action="createTrans.php">
<div>Token:</div>
<select name="token">
<?php
	$data = file_get_contents('./data/token.txt', true);
	
	$token_Arr = explode("\n", trim($data));	
	
	foreach($token_Arr as $value){
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>
</select>
</br>
</br>
<div>Update Existing:&nbsp;</div>
		<select name="updateExisting" id="actionType">
			<option value="true">YES</option>
			<option value="false">No</option>
		</select></br>
		<input type="hidden" name="_act" value="updatePaymentMethod" />
		<input type="text" name="billingAddressId" value="" />
		<input type="text" name="streetAddress" value="100 Maple Lane" />
		</br></br>
		
		<div id="dropin2" style="width:400px; float:none;"></div>
        <input type="submit" id="bttn1" value="Update Payment Method">
    </form>

</div>

<div style="width:400px;">
<h3>Find Payment Method</h3>

<form method="post" action="createTrans.php">
<div>Token:</div>
<select name="token">
<?php
	$data = file_get_contents('./data/token.txt', true);
	
	$token_Arr = explode("\n", trim($data));	
	
	foreach($token_Arr as $value){
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>
</select>
</br>
</br>

		<input type="hidden" name="_act" value="findPaymentMethod" />
		
        <input type="submit" value="Find Payment Method">
    </form>

</div>

<div style="width:400px;">
<h3>Delete Payment Method</h3>

<form method="post" action="createTrans.php">
<div>Token:</div>
<select name="token">
<?php
	$data = file_get_contents('./data/token.txt', true);
	
	$token_Arr = explode("\n", trim($data));	
	
	foreach($token_Arr as $value){
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>
</select>
</br>
</br>

		<input type="hidden" name="_act" value="deletePaymentMethod" />
		
        <input type="submit" value="Delete Payment Method">
    </form>

</div>
</div>



<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script>
	var clientToken = '<?= $clientToken ?>';

	braintree.setup(clientToken,'dropin', {
	   container: 'dropin', 
	   singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
	});
	
	braintree.setup(clientToken,'dropin', {
	   container: 'dropin2', 
	   singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
	});
	
	function dropChange2(){
		if(document.getElementById('actionType').value == "findCustomer"){
			document.getElementById("bttn1").value = "Find Customer";
		}else{
			document.getElementById("bttn1").value = "Delete Customer";
		}
	}
	
	
	function dropChange(){
		if(document.getElementById('storedPayment').value == "true"){
			//With Customer ID
			
			braintree.setup(clientToken, 'dropin', {
			   container: 'dropin', 
			   singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
			});
			
		//	location.reload();
		}else{
			var list = document.getElementById("myform");   // Get the <ul> element with id="myList"
			
			console.log(list.childNodes[44]);
			list.removeChild(list.childNodes[44]);   
				
		
			document.getElementById("dropin").innerHTML = '';
				//document.getElementsByName("payment_method_nonce").remove();
		}
				
		
	//	document.getElementById("dropin").style.display = 'none';
	};

	function submitForm(){
		if(document.getElementById('storedPayment').value != "true"){
			document.forms["myform"].submit();
		}
	}

</script>