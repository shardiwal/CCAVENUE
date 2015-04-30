<?php
	class CCAVENUE {

		private $ccavenue_fields = array(
		    'Merchant_Id',
		    'Currency',
		    'Amount',
		    'TxnType',
		    'Order_Id',
		    'actionID',
		    'Redirect_Url',
		    'Checksum',
		    'billing_cust_name',
		    'billing_cust_address',
		    'billing_cust_country',
		    'billing_cust_state',
		    'billing_cust_city',
		    'billing_zip_code',
		    'billing_cust_tel',
		    'billing_cust_email',
		    'delivery_cust_name',
		    'delivery_cust_address',
		    'delivery_cust_country',
		    'delivery_cust_state',
		    'delivery_cust_tel',
		    'delivery_cust_notes',
		    'delivery_cust_city',
		    'delivery_zip_code',
		    'Merchant_Param',
		);

		public $actionid     = 'TXN',
			   $txntype      = 'A',
			   $button_label = 'Pay',
			   $GATEWAY_URL  = 'https://www.ccavenue.com/shopzone/cc_details.jsp';

		public function __construct($args)
		{
		    if ( !$args ) {
		        throw new Exception(
		        'Error: Not enough parameters (
		        Constructor array("working_key" => "xyz123", "merchant_id" => "OPD")
		        ) !!!\n');
		    }

		    foreach ($args as $key => $value) {
		    	$this->add_attribute( strtolower($key), $value );
		    }
		}

        public function generate_ccavenue_pay_button()
        {

		    // Generate checksum
		    $this->checksum = $this->_getCheckSum(
        		$this->merchant_id,
        		$this->amount,
        		$this->order_id,
        		$this->redirect_url,
        		$this->working_key
        	);

            $ccavenue_args = array();
            foreach ($this->ccavenue_fields as $field) {
            	$object_field = strtolower($field);
            	$ccavenue_args[ $field ] = $this->$object_field;
            }

			$ccavenue_args_array = array();
			foreach($ccavenue_args as $param => $value) {
				$ccavenue_args_array[] = "<input type='hidden' name='$param' value='$value'/>";;
			}

			$method = $this->GATEWAY_URL;
			$button_label = $this->button_label;
			$form = "<form action='$method' method='post'>".
			implode( '', $ccavenue_args_array ) .
			"<div class='payment_buttons'>
				<input type='submit' class='button alt' value='$button_label'>
			</div>
			</form>";
			return $form;
		}

		private function _getchecksum() 
		{
			$fields = array(
	    		$this->merchant_id,
	    		$this->order_id,
	    		$this->amount,
	    		$this->redirect_url,
	    		$this->working_key
    		);
		    $str   = implode('|', $fields); 
		    $adler = $this->_adler32($str); 
		    return $adler; 
		}

		private function _adler32($str) 
		{
			$adler = 1;
			$BASE  = 65521 ;   
			$s1    = $adler & 0xffff ; 
			$s2    = ($adler >> 16) & 0xffff; 
			for($i = 0 ; $i < strlen($str) ; $i++) 
			{ 
				$s1 = ($s1 + Ord($str[$i])) % $BASE ; 
				$s2 = ($s2 + $s1) % $BASE ; 
			} 
			return $this->_leftshift($s2 , 16) + $s1; 
		}  

		private function _leftshift($str , $num) 
		{   
		    $str = DecBin($str);   
		    for( $i = 0 ; $i < (64 - strlen($str)) ; $i++) 
		        $str = "0".$str ;   
		    for($i = 0 ; $i < $num ; $i++) 
		        $str = $str."0"; $str = substr($str , 1 ) ;  
		    return $this->_cdec($str) ; 
		}

		private function _cdec($num) 
		{   
		    for ($n = 0 ; $n < strlen($num) ; $n++) 
		    { 
		        $temp = $num[$n] ; 
		        $dec = $dec + $temp*pow(2 , strlen($num) - $n - 1); 
		    }   
		    return $dec; 
		}

	    public function add_attribute($propertyName, $propertyValue){
	        $this->{$propertyName} = $propertyValue;
	    }

	}
?>