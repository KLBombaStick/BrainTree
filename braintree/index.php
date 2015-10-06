<?php
/*
pp_michael
*/
require_once 'lib/Braintree.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('5xwqxqd7pkqwxfjs');
Braintree_Configuration::publicKey('8pfkt34wzyvrnnwy');
Braintree_Configuration::privateKey('8b63a0ae59085a11912527848633b848');

$aCustomerId = "37278709";

$clientToken = Braintree_ClientToken::generate();


//[WITHT THE CUSTOMER ID]
$clientToken2 = Braintree_ClientToken::generate(array(
	"customerId" => $aCustomerId
));



if(isset($_GET['act'])){
	$data = array(
		"client_token" => $clientToken,
	);
	
	echo json_encode($data);
	exit();
}


?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
	<script>
	$('document').ready(function(){
		//[SWAP TAB]
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			
			var target = $(e.target).attr("href") // activated tab
			//alert(target);
			window.location = target;
		});
	});
	</script>
	
	<style>
		body {
			position: relative; 
		}
		#dropDiv {padding-top:50px;height:500px;}
		#singleDiv {padding-top:50px;height:200px;}
		#hostedDiv {padding-top:50px;height:300px;}
		#settleDiv {padding-top:50px;height:300px;}
		#refundDiv {padding-top:50px;height:300px;}
		#payCustomerID {padding-top:50px;height:200px;}
		#createDiv {padding-top:50px;height:900px;}
		#createPaymentDiv {padding-top:50px;height:500px;}
		#TokenDiv {padding-top:50px;height:500px;}
		
	</style>
</head>

<body data-spy="scroll" data-target="#navbar" >
<div class="page-header" >
  <h2>BrainTree <small>pre-work</small></h2>
</div>
<div class="container" style="margin-top:10px;">
  <div class="row">
  
    <div class="col-sm-3" id="navbar">
      <!--Sidebar content-->
		<ul class="nav nav-pills nav-stacked">	
			<li role="presentation" class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				  Payment <span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="#dropDiv" data-toggle="tab">Drop-in</a></li>
					<li><a href="#singleDiv" data-toggle="tab" >Single Use</a></li>
					<li><a href="#hostedDiv" data-toggle="tab" >Hosted Fields</a></li>
					<li><a href="#settleDiv" data-toggle="tab" >Submit Settlement</a></li>
					<li><a href="#refundDiv" data-toggle="tab" >Refund</a></li>
					<li><a href="#payCustomerID" data-toggle="tab" >Pay by customer ID</a></li>
				</ul>
			</li>
			
			<li role="presentation" class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				  Customer <span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="#createDiv" data-toggle="tab">Create Customer</a></li>
					<li><a href="#createPaymentDiv" data-toggle="tab">Create Payment Method</a></li>
					<li><a href="#TokenDiv" data-toggle="tab">Charge by Token</a></li>
					<li><a target="_blank" href="Customer.php">Others</a></li>
				</ul>	
			</li>
			<li role="presentation" class="dropdown">
				<a target="_blank"  onclick="getData('submerchant.php'); return false"  href="submerchant.php">Market Place</a>
			</li>
			<li role="presentation" class="dropdown">
				<a target="_blank" onclick="getData('webhooklogs.php'); return false" href="webhooklogs.php">Webhook logs</a>
			</li>
			<li role="presentation" class="dropdown">
				<a target="_blank" onclick="getData('report.php');  return false" href="report.php">Report</a>
			</li>
		</ul>
    </div>
	
	
    <div class="col-sm-8">
      <!--Body content-->
		<div id="dropDiv">
		  <h3>Drop-in</h3>
			<form method="post" action="createTrans.php">
				<div id="dropin"></div>
				
				<div class="col-md-4">Submit For Settlement:</div>
				<div class="col-md-8">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-primary active">
							<input type="radio" name="SFS" id="option1" value="1" autocomplete="off" checked> YES
						</label>
						<label class="btn btn-primary">
							<input type="radio" name="SFS" id="option2" value="0" autocomplete="off"> No
						</label>
					</div>
				</div></br>
				<span id="helpBlock" class="help-block">Yes - It'll do a full settlement, when the batch execute. You can also do it submit the payment</span>
			
				<div class="col-md-4">Store In Vault:</div>
				<div class="col-md-8">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-primary active">
							<input type="radio" name="SIV" id="option1" value="1" autocomplete="off" checked> YES
						</label>
						<label class="btn btn-primary">
							<input type="radio" name="SIV" id="option2" value="0" autocomplete="off"> No
						</label>
					</div>
				</div></br>
				<span id="helpBlock" class="help-block">Yes - It'll save the funding source, return with the customer ID for the next charge.</br></span>

				</br>
				<input class="btn btn-primary btn-sm" type="submit" value="Pay">
			</form>
		</div>
		<hr>
		<div id="singleDiv">
			<h3>Single Use</h3>
			<form method="post" action="createTrans.php">
				<div id="dropinS"></div>
				
				<span id="helpBlock" class="help-block">For a PayPal Single Payment, Billing and Shipping Address, Nonce will be return.</br>
				*This will auto submit after return from PayPal</span>

				</br>
				<!--<input class="btn btn-primary btn-sm" type="submit" value="Pay">	-->
				
				<div id="div_details"></div>
			</form>
		</div>
		<hr>
		<div id="hostedDiv">
			<h3>Hosted Fields</h3>
			<form  method="post"  action="createTrans.php" id="hosted-form">
				<label for="card-number">Card Number</label>
				<div style="height:20px; border-bottom: 1px solid #666; margin:5px; padding:0px;" id="card-number"></div>

				<label for="cvv">CVV</label>
				<div style="height:20px;  border-bottom: 1px solid #666; margin:5px; padding:0px;" id="cvv"></div>

				<label for="expiration-date">Expiration Date</label>
				<div style="height:20px;  border-bottom: 1px solid #666; margin:5px;" id="expiration-date"></div>

				<input class="btn btn-primary btn-sm" type="submit" value="Pay">
				<div id="err_mgs"></div>
				<input id="nonce" name="payment_method_nonce" type="hidden" value="" />
				
				<span id="helpBlock" class="help-block">Nonce console as request, also display after submit the payment</span>
			</form>
		</div>
		
		<div id="settleDiv">
			<h3>Submit Settlement</h3>
			<form  method="post"  action="createTrans.php" id="hosted-form">
				<input class="form-control" id="s_transid" type="text" placeholder="Transaction ID" ></br>
				<input class="form-control" id="s_amt" type="text" placeholder="Amount" >
				</br>
				<input class="btn btn-primary btn-sm" id="s_input"  onclick="return false" type="submit" value="Submit Settlement">
			</form>
		</div>
		
		<div id="refundDiv">
			<h3>Refund</h3>
			<form  method="post"  action="createTrans.php" id="hosted-form">
				<input class="form-control" id="r_transid" type="text" placeholder="Transaction ID 4xhz4j" ></br>
				<input class="form-control" id="r_amt" type="text" placeholder="Amount" >
				</br>
				<input class="btn btn-primary btn-sm" id="r_input" onclick="return false" type="submit" value="Refund">
				
			</form>
		</div>
		
		
		<div id="payCustomerID">
			<h3>With Customer ID</h3>
			<form method="post" action="createTrans.php">
				<div id="dropinCustID"></div>
				<br/>
				<input class="btn btn-primary btn-sm" type="submit" value="Pay ME">
			</form>
		</div>
		
		<hr>
		<div id="createDiv">
			<h3>Create Customer</h3>
			<form method="post" action="createTrans.php" id="ccForm">
				
				<div>Store Payment Method:</div>
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-primary active">
						<input type="radio" name="store" id="option1" value="1" onchange="dropChange(this, 'cc_dropin')" autocomplete="off" checked> Yes
					</label>
					<label class="btn btn-primary">
						<input type="radio" name="store" id="option2" value="0" onchange="dropChange(this, 'cc_dropin')" autocomplete="off"> No
					</label>
				</div></br></br>
				
				<input type="hidden" name="_act" value="createCustomer" />
				<input class="form-control" name="firstName" type="text" value="" placeholder="First Name" ></br>
				<input class="form-control" name="lastName" type="text" value="" placeholder="Last Name" ></br>
				<input class="form-control" name="company" type="text" value="" placeholder="Company Name" ></br>
				<input class="form-control" name="email" type="text" value="" placeholder="Email" ></br>
				<input class="form-control" name="phone" type="text" value="" placeholder="Phone eg:281.330.8004" ></br>
				<input class="form-control" name="fax" type="text" value="" placeholder="fax eg:419.555.1235" ></br>
				<input class="form-control" name="website" type="text" value="" placeholder="Website eg:http://example.com" ></br>
				<input class="form-control" name="custom1" type="text" value="" placeholder="Your Custom Value 1" ></br>
				<input class="form-control" name="custom2" type="text" value="" placeholder="Your Custom Value 2" ></br>
				
				
				<div id="cc_dropin" style="width:400px; float:none;"></div>
				<br/>
				<input class="btn btn-primary btn-sm" id="cc_input" onclick="return false" type="submit" value="Create Customer">
			</form>
		</div>	
		<hr>
		<div id="createPaymentDiv">
			<h3>Create Payment Method</h3>
			<form method="post" id="cpm_form" action="createTrans.php">
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
					<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-primary active">
						<input type="radio" name="verify" id="option1" value="1" autocomplete="off" checked> Yes
					</label>
					<label class="btn btn-primary">
						<input type="radio" name="verify" id="option2" value="0" autocomplete="off"> No
					</label>
					</div></br></br>
					
					
					<div id="cPaymentMethod"></div>
					
					<input type="hidden" name="_act" value="createPaymentMethod" />
					</br></br>
						
					<input class="btn btn-primary btn-sm" id="cpm_input" type="submit" value="Create Payment Method">
			</form>
		</div>
		
		<hr>
		<div id="TokenDiv">
			<h3>Bill By Token</h3>
			<form method="post" action="createTrans.php" id="pbtForm">
				<div>token:</div>
				<select name="token" id="token">
				<?php
					$data = file_get_contents('./data/token.txt', true);
					
					$token_Arr = explode("\n", trim($data));	
					
					foreach($token_Arr as $value){
						echo '<option value="'.$value.'">'.$value.'</option>';
					}					
				?>
				</select>
				</br></br>
				
				<input type="hidden" name="_act" value="payByToken" />
				<input class="form-control" name="amt" type="text" value="" placeholder="Amount" ></br>
				
				
				<div id="cc_dropin" style="width:400px; float:none;"></div>
				<br/>
				<input class="btn btn-primary btn-sm" id="pbt_input" onclick="return false" type="submit" value="Create Customer">
			</form>
		</div>	
		<hr>
		
		
		</br>
    </div>
  </div>
</div>




<!--//[MODAL ]-->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Payment Response</h4>
      </div>
      <div class="modal-body">
		
      </div>
      <div class="modal-footer">
	  <!--	[I don't need the button here]]
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
		-->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<footer><small>Braintree Pre-work By Michael Chong</small></footer>
</body>


<script>
//[STYLE FOR CUSTOM HOSTED FIELD]
var hostStyle = {
    // Style all elements
    "input": {
      "font-size": "16pt",
      "color": "#2E9AFE",
    },

    // Styling a specific field
    ".number": {
      "font-family": "monospace"
    },

    // Styling element state
    ":focus": {
      "color": "#2E9AFE"
    },
    ".valid": {
      "color": "#666"
    },
    ".invalid": {
      "color": "red"
    },

    // Media queries
    // Note that these apply to the iframe, not the root window.
    "@media screen and (max-width: 700px)": {
      "input": {
        "font-size": "14pt",
        "padding-bottom": "20px"
      }
    }
};

function getData(url){
	$.post( url, { _act: ''}, function( data ) {
		$('#myModal .modal-body').html(data);
		$('#myModal').modal('show');
	});
}
	
//[PROCESS SETTLEMENT]
$('#s_input').click(function(){
	$.post( "createTrans.php", { _act: 'settlement', amt: $('#s_amt').val(), id: $('#s_transid').val()}, function( data ) {
		$('#myModal .modal-body').html(data);
		$('#myModal').modal('show');
		
	});
	
	return false;
});	

//[PROCESS REFUND]
$('#r_input').click(function(){
	$.post( "createTrans.php", { _act: 'refund_process', amt: $('#r_amt').val(), id: $('#r_transid').val()}, function( data ) {
		$('#myModal .modal-body').html(data);
		$('#myModal').modal('show');
		
	});
	
	return false;
});	

$('#cc_input').click(function(){
	$.post( "createTrans.php", { _act: 'createCustomer', data: $("#ccForm").serialize()}, function( data ) {
		$('#myModal .modal-body').html(data);
		$('#myModal').modal('show');
		
	});
	
	return false;
});	


$('#pbt_input').click(function(){
	$.post( "createTrans.php", { _act: 'payByToken', data: $("#pbtForm").serialize()}, function( data ) {
		$('#myModal .modal-body').html(data);
		$('#myModal').modal('show');
		
	});
	
	return false;
});
	
//[script for the dropin]
braintree.setup("<?= $clientToken ?>",'dropin', {
   container: 'dropin', 
   singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
   onPaymentMethodReceived: function(obj){
	   console.log(obj);
		$.post( "createTrans.php", { payment_method_nonce: obj.nonce}, function( data ) {
			$('#myModal .modal-body').html('<span>This is the nonce that applied on this transaction: '+ obj.nonce + ', </br>Payment Method: '+ obj.type + '</span></br>' 
			+ data);
			$('#myModal').modal('show');
			
		});
		
		return false;
	}
});

//[script for the single use]
braintree.setup("<?= $clientToken ?>",'paypal', {
	container: 'dropinS', 
	singleUse: true, 
	amount: 10.00, 		//[Only require for single]
	currency: 'USD',		//[Only require for single]
	enableShippingAddress: true,
	enableBillingAddress: true,
	onPaymentMethodReceived: function(obj){
		console.log(obj);
		var ba = '', sa = '';
		
		for(var x in obj.details){
				for(var y in obj.details[x]){
					if(x == 'billingAddress' && y != undefined){
						ba += y + ': ' + obj.details[x][y] + '</br>';
					}else if(x == 'shippingAddress'  && y != undefined){
						sa += y + ': ' + obj.details[x][y] + '</br>';
					}
				}
		}
		
		console.log(ba);
		console.log(sa);
		
		$.post( "createTrans.php", { payment_method_nonce: obj.nonce}, function( data ) {
			$('#myModal .modal-body').html('<span>This is the nonce that applied on this transaction: '+ obj.nonce + ', </br>Payment Method: '+ obj.type + 
			'</br>Biling Address:</span></br>' 
			+ ba + '</br>' + 
			', </br>Shipping Address:</span></br>' 
			+ sa + '</br>'
			+ data);
			$('#myModal').modal('show');
			
		});
		
		return false;
	}
});



//[script for hosted field]
braintree.setup("<?= $clientToken ?>", "custom", {
	id: "hosted-form",
	hostedFields: {
		styles: hostStyle,
		number: {
			selector: "#card-number",
			placeholder: "411111111111111"
		},
		cvv: {
			selector: "#cvv",
			placeholder: "123"
		},
		expirationDate: {
			selector: "#expiration-date",
			placeholder: "MM/YY"
	}
	}
	,onPaymentMethodReceived: function(obj){
		console.log(obj.nonce);	//[LOG THE nonce as request]
		$('#nonce').val(obj.nonce);
		
		$.post( "createTrans.php", { payment_method_nonce: obj.nonce}, function( data ) {
			$('#myModal .modal-body').html('<span>This is the nonce that applied on this transaction: ' + obj.nonce + '</span></br>'+ data);
			$('#myModal').modal('show');
			
		});
	//	}, "json");
		return false;
	},onError: function(obj){
		var msg = '';
		$('#err_mgs').html('');
		
		for(var x in obj){
			if(x == 'type'){
				msg += '<span style="font-weight:bold; color:red;">'+ obj[x] + '</span>: ';
			}else if(x == 'message'){
				msg += '<span style="color:red;">Hello friend, '+ obj[x] + '</span>';
			}
			
				if(obj[x]['invalidFieldKeys'] != undefined){
					msg += '<span style="color:red;">Please check on the following fields: ';
					
					$.each(obj[x]['invalidFieldKeys'], function( i, l ){
						msg += "</br>[" + l + "]";
					});
					msg += '</span>'; 
					
				}
			
			
			msg += '</br>';
		}
		
		$('#err_mgs').append(msg);
		return false;
	}
});


//With Customer ID
braintree.setup("<?= $clientToken2 ?>",'dropin', {
   container: 'dropinCustID', 
   singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
});



//[CREATE CUSTOMER drop in]
braintree.setup("<?= $clientToken ?>",'dropin', {
	container: 'cc_dropin', 
	singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
	onReady: function (integration) {
		checkout = integration;
	}
});

braintree.setup("<?= $clientToken ?>",'dropin', {
	container: 'cPaymentMethod', 
	singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
	onPaymentMethodReceived: function(obj){
		console.log(obj.nonce);	//[LOG THE nonce as request]
		
			$.post( "createTrans.php", { _act: 'createPaymentMethod', data: $("#cpm_form").serialize(), payment_method_nonce: obj.nonce}, function( data ) {
			$('#myModal .modal-body').html(data);
			$('#myModal').modal('show');
			
		});
		
		return false;
	}
});



//[CONTROL DROPIN FOR CREATED CUSTOMER]
function dropChange(opt, containerID){
	if(opt.value == 1){
		//[CREATE CUSTOMER drop in]
		braintree.setup("<?= $clientToken ?>",'dropin', {
			container: containerID, 
			singleUse: false,  //[Do not require to pass, unless you're using PayPal instead of dropin]
			onReady: function (integration) {
				checkout = integration;
			}
		});
	}else{
		// When you are ready to tear down your integration
		checkout.teardown(function () {
		  checkout = null;
		  // braintree.setup can safely be run again!
		});
	}
};



</script>`