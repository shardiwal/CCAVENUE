# CCAVENUE Payment Gateway

php curl_init need to be available to use this module

## VERSION = 1.0;

## Constructor

	$cc = new CCAVENUE( array(
		'working_key'  => 'working key',
		'merchant_id'  => 'merchant id',
		'currency' 	   => 'INR',
	) );

	$cc->Redirect_Url = 'http://localhost/index.html';
	$cc->amount 			= 10;
	$cc->order_id 		= 'ORD_10';

	$cc->billing_cust_name 		  = 'Customer Name';
	$cc->billing_cust_address 	= 'Address';
	$cc->billing_cust_country 	= 'Country';
	$cc->billing_cust_state 		= 'State';
	$cc->billing_cust_city 			= 'City';
	$cc->billing_zip_code 			= 'Post Code';
	$cc->billing_cust_tel 			= 'Telephone Number';
	$cc->billing_cust_email 		= 'Email Address';

	$cc->delivery_cust_name 		= 'Customer Name';
	$cc->delivery_cust_address 	= 'Address';
	$cc->delivery_cust_country 	= 'Country';
	$cc->delivery_cust_state 		= 'State';
	$cc->delivery_cust_city 		= 'City';
	$cc->delivery_zip_code 			= 'Post Code';
	$cc->delivery_cust_tel 			= 'Telephone Number';

	$cc->delivery_cust_notes 		= 'Delivery Customer Note';

	// Custom button label
	$cc->button_label = 'Click to make payment';

	$cc->generate_pay_button();

	// Do this inside your return url function
	// Get the payment status
	$cc->status();


## Public Methods

### list_params

		List CCAVENUE Parameters
		return: FIELDS ARRAY

### generate_pay_button

		Generate pay button
		To change the button label, button_label => 'Make Payment';

### status

		-- Order Status Tracker --
		Don't relay on real-time parameters from Avenues

		This method will return the order details of only the recent transactions not older than 30 minutes.

		accepts:
			$is_old_order - make true, if you want to get the order details of any transaction older by 45 minutes.

		returns:
		Y - IF payment was successfull
		N - IF payment was failed
		B - IF status is not cleared

### getchecksum

		Get Checksum for CCAVENUE

