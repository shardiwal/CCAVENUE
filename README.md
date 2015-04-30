# CCAVENUE
CCAVENUE Payment Gateway

	require('CCAVENUE.php');

	$form = new CCAVENUE( array(
		'working_key'  => 'workingkey',
		'merchant_id'  => 'merchant id',
		'currency' 	   => 'INR',
	) );

	$form->redirect_url = 'http://test/index.html';
	$form->amount       = 10;
	$form->order_id 	= 'ORD_10';

	$form->billing_cust_name		= 'Lalchand';
	$form->billing_cust_address 	= 'Jaipur';
	$form->billing_cust_country 	= 'India';
	$form->billing_cust_state 		= 'Rajasthan';
	$form->billing_cust_city		= 'Jaipur';
	$form->billing_zip_code		    = '302021';
	$form->billing_cust_tel 		= '1233';
	$form->billing_cust_email 		= 'test@test1.com';
	$form->delivery_cust_name 		= 'Lalchand Home';
	$form->delivery_cust_address 	= 'Jobner';
	$form->delivery_cust_country 	= 'India';
	$form->delivery_cust_state 		= 'Rajasthan';
	$form->delivery_cust_city		= 'Jobner';
	$form->delivery_zip_code 		= '341302';
	$form->delivery_cust_tel 		= '23423423';
	$form->delivery_cust_notes 		= 'Mobile Added';

	echo $form->generate_ccavenue_pay_button();