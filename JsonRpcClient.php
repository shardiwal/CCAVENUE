<?php
/**
* PHP implementation of JSON-RPC 2.0
* Client
* Specification: http://www.jsonrpc.org/specification
*
* @package JsonRpcPhp
* @author Mark Rolich <mark.rolich@gmail.com>
*/

class JsonRpcClient
{
    /**
    * @var mixed - cURL resource
    */
    private $curl;

    /**
    * @var string - last JSON-RPC Request
    */
    private $lastRequest;
	
	/**
	 * @var string $url
	 */
	 private $url;

    /**
    * Constructor
    * Initializes cURL session
    *
    * @param $url string - JSON-RPC server url
    */
    public function __construct($url="")
    {
    	if($url)
        	$this->curl = curl_init($url);
    }
	
	public function setURL($url)
	{
		$this->url = $url;
		$this->curl = curl_init($url);
	}

    /**
    * Getter for last JSON-RPC Request
    *
    * @return string - last JSON-RPC Request
    */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
    * Overloads JSON-RPC server method to call in client context
    *
    * @param $method - method name
    * @param $args - array or object of method parameters and JSON-RPC Request id
    * @return string - JSON-RPC Response
    */
    public function __call($method, $args)
    {
        $params = $args[0];
        $id = (isset($args[1])) ? $args[1] : null;
        return $this->call($method, $params, $id);
    }

    /**
    * Performs JSON-RPC raw call
    *
    * @param $request mixed - JSON-RPC Request
    * @return string - JSON-RPC Response
    */
    public function rawcall($request="", $POST = TRUE)
    {
    	$options = array();
		
		if($POST)
		{
			$this->lastRequest = (is_string($request)) ? stripslashes($request) : $request;
	        $options = array(
	            CURLINFO_CONTENT_TYPE => "application/json",
	            CURLOPT_POST => $POST,
	            CURLOPT_POSTFIELDS => $request,
	            CURLOPT_RETURNTRANSFER => true
	        );
		}
		else
			$options = array(
	            CURLINFO_CONTENT_TYPE => "application/json",
	            CURLOPT_RETURNTRANSFER => true
	        );

        curl_setopt_array($this->curl, $options);

		$response   = curl_exec($this->curl);
        $info       = curl_getinfo($this->curl);
        $error      = curl_error($this->curl);

        if ($error !== '') {
            throw new \RuntimeException($error);
        } elseif ($info['http_code'] !== 200) {
			throw new \RuntimeException(sprintf("Server responded with %d HTTP code.", $info['http_code']));
        }

        if ($response !== '') {
            return $response;
        }
    }

    /**
    * Performs JSON-RPC batch call
    *
    * @param $requests mixed - array of JSON-RPC Requests
    * @return string - JSON-RPC Response
    */
    public function callBatch($requests)
    {
        return $this->rawcall(json_encode($requests));
    }

    /**
    * Prepares JSON-RPC Request and performs JSON-RPC call
    *
    * @param $method string - method name
    * @param $params mixed - array or object of parameters
    * @param $id mixed - JSON-RPC request id (null if is notification)
    * @return string - JSON-RPC Response
    */
    public function call($method, $params = null, $id = null)
    {
        $request = json_encode($this->prepare($method, $params, $id));
        return $this->rawcall($request);
    }

    /**
    * Builds JSON-RPC Request object
    *
    * @param $method string - method name
    * @param $params  - array or object of parameters
    * @param $id mixed - JSON-RPC Request id
    * @return string - JSON-RPC Request
    */
    public function prepare($method, $params, $id)
    {
        $request = new \stdClass();

        $request->jsonrpc = '2.0';
        $request->method = $method;

        if ($params !== null) {
            $request->params = $params;
        }

        if ($id !== null) {
            $request->id = $id;
        }

        return $request;
    }

    /**
    * Destructor
    * Closes cURL session
    */
    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }
}
?>