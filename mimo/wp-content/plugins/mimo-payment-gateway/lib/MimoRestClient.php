<?php

/**
 * MIMO REST API Library for PHP
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * @package   MIMO
 * @copyright Copyright (c) 2012 Mimo Inc. (http://www.mimo.com.ng)
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   1.2.6
 * @link      http://www.mimo.com.ng
 */

if (!function_exists('curl_init')) { 
    throw new Exception("Mimo's API Client Library requires the CURL PHP extension.");
}

if (!function_exists('json_decode')) {
    throw new Exception("Mimo's API Client Library requires the JSON PHP extension.");
}

/**
 * MIMO API Library for PHP
 *
 * @package   MIMO
 * @copyright Copyright (c) 2012 Mimo Inc. (http://www.mimo.com.ng)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class MimoRestClient
{

    /**
     * @var string Mimo API key
     */
    private $apiKey;

    /**
     * @var string Mimo API key
     */
    private $apiSecret;

    /**
     * @var string oauth token
     */
    private $accessToken;

    /**
     *
     * @var string URL to return the user to after the authentication request
     */
    private $redirectUri;
    /**
     * @var string error messages returned from Mimo
     */
    private $errorMessage = false;

    const API_SERVER = "https://staging.mimo.com.ng/oauth/v2/";
    const USER_API_SERVER = "https://staging.mimo.com.ng/partner/";
    /**
     * Sets the initial state of the client
     * 
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $redirectUri
     * @param string $mode 
     * @throws InvalidArgumentException
     */
    public function __construct($apiKey = false, $apiSecret = false, $redirectUri = false)
    {
	
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->redirectUri = $redirectUri;
        $this->apiServerUrl = self::API_SERVER;
        $this->apiServerUrlUser=self::USER_API_SERVER; 
    }
    /**
     * Get oauth authenitcation URL
     * 
     * @return string URL
     */
    public function getAuthUrl($requesturi='')
    {
        $params = array(
            'client_id' => $this->apiKey,
	        	'url' => $this->redirectUri,
            'response_type' => 'code'
        );
        // Only append a redirectURI if one was explicitly specified
        if($requesturi!=''){
        	$params['url'] = $requesturi;
        }
        else if ($this->redirectUri) {
            $params['url'] = $this->redirectUri;
        }
        $url =  $this->apiServerUrl.'/authenticate?' . http_build_query($params);
        return $url;
    }
    /**
     * Request oauth token from Mimo
     * 
     * @param string $code User authorization code returned from Mimo
     * @return string oauth token
     */
    public function requestToken($code)
    { 
        if (!$code) {
            return $this->setError('Please pass an oauth code.');
        }
       
        $params = array(
            'client_id' => $this->apiKey,
            'client_secret' => $this->apiSecret,
            'url' => $this->redirectUri,
            'grant_type' => 'authorization_code',
            'code' => $code
        );
        $url =  $this->apiServerUrl.'token?' . http_build_query($params);
       
        $response = $this->curl($url, 'POST');
      
        if (isset($response['error'])) {
            $this->errorMessage = $response['error_description'];
            return false;
        }
     	$this->setToken( $response['access_token']);
        return $response['access_token'];
    }

    /**
     * Grabs the basic account information for
     * the provided Mimo user search parameter
     * 
     * @param string $user Mimo Account Field like username, email, phone, account_number
     * @param string $datastring Mimo Account value of username, email, phone, account_number you wanted to get searched.
     * @return array Basic user information 
     */
    
    public function getUser($user,$datastring)
    { 
        $params = array(
            $user => $datastring
        );
        $url = $this->apiServerUrlUser.'user/card_id';
        $response = $this->get($url, $params,true);
        if (isset($response['error'])) {
        	$this->errorMessage = $response['error_description'];
        	return false;
        }
       return $response;
    }
    /**
     * Grab information for the given transaction ID
     *
     * @param float amount to which information is pulled
     * @return array Transaction information
     */
    public function transaction($amount = false,$notes='')
    {
    	// Verify required paramteres
    	if (!$amount) {
    		return $this->setError('Please enter a transaction ID.');
    	}
    	$params = array('notes'=>$notes,
    			'amount' => $amount
    			
    	);
    	$url = $this->apiServerUrlUser.'transfers';  
    	$data['notes'] = $params['notes'];
		$data['amount'] = $params['amount'];
    	$response = $this->post($url, $data,true);
    	if (isset($response['error'])) {
    		$this->errorMessage = $response['error_description'];
    		return false;
    	}
    	return $response;
    }
    /**
     * Refund Money for the given transaction ID
     *
     * @param float amount to which information is pulled
     * @return array Transaction information
     */
    public function refund($amount = false,$transaction_id="",$notes='')
    {
    	// Verify required paramteres
    	if (!$amount) {
    		return $this->setError('Please enter amount.');
    	}
    	if (!$transaction_id) {
    		return $this->setError('Please enter Transaction ID.');
    	}
    	$params = array('notes'=>$notes,
    			'amount' => $amount,
    			'transaction_id'=>$transaction_id
    
    	);
    	$url = $this->apiServerUrlUser.'refunds';
    	$data['notes'] = $params['notes'];
    	$data['amount'] = $params['amount'];
    	$data['transaction_id'] = $params['transaction_id'];
    	$response = $this->post($url, $data,true);
    	if (isset($response['error'])) {
    		$this->errorMessage = $response['error_description'];
    		return false;
    	}
    	return $response;
    }
    /**
     * Void the transaction using transaction ID
     *
     * @param int transaction is  to which information is pulled
     * @return string Transaction information
     */
    public function void($transaction_id="")
    {
    	// Verify required paramteres
    	if (!$transaction_id) {
    		return $this->setError('Please enter Transaction ID.');
    	}
    	$params = array('transaction_id'=>$transaction_id 	);
    	$url = $this->apiServerUrlUser.'transfers/void';
    	$data['transaction_id'] = $params['transaction_id'];
    	$response = $this->post($url, $data,true);
    	if (isset($response['error'])) {
    		$this->errorMessage = $response['error_description'];
    		return false;
    	}
    	return $response;
    }
    /**
     * @return string|bool Error message or false if error message does not exist
     */
    public function getError()
    {
        if (!$this->errorMessage) {
            return false;
        }

        $error = $this->errorMessage;
        $this->errorMessage = false;

        return $error;
    }

    /**
     * @param string $message Error message
     */
    protected function setError($message)
    {
        $this->errorMessage = $message;
    }

    /**
     * Parse API response
     * 
     * @param array $response
     * @return array
    */
    protected function parse($response)
    {
        if (!$response['Success']) {
            $this->errorMessage = $response['Message'];

            // Exception for /register method
            if ($response['Response']) {
                $this->errorMessage .= " :: " . json_encode($response['Response']);
            }

            return false;
        }

        return $response['Response'];
    }

    /**
     * Executes POST request against API
     * 
     * @param string $request
     * @param array $params
     * @param bool $includeToken Include oauth token in request?
     * @return array|null 
     */
    protected function post($request, $params = false, $includeToken = true)
    {
				$params['access_token'] = $this->accessToken;        
        $delimiter = (strpos($request, '?') === false) ? '?' : '&';
        $url =  $request . $delimiter . http_build_query($params);
        $rawData = $this->curl($url, 'POST',array());
        return $rawData;
    }

    /**
     * Executes GET requests against API
     * 
     * @param string $request
     * @param array $params
     * @return array|null Array of results or null if json_decode fails in curl()
     */
    protected function get($request, $params = array())
    { 
        $params['access_token'] = $this->accessToken;        
        $delimiter = (strpos($request, '?') === false) ? '?' : '&';
        $url =  $request . $delimiter . http_build_query($params);
	      
	$rawData = $this->curl($url, 'GET');
        return $rawData;
    }

    /**
     * Execute curl request
     * 
     * @param string $url URL to send requests
     * @param string $method HTTP method
     * @param array $params request params
     * @return array|null Returns array of results or null if json_decode fails
     */
    protected function curl($url, $method = 'GET', $params = array())
    {
        // Encode POST data
        $data = json_encode($params);
        // Set request headers
        $headers = array('Accept: application/json', 'Content-type: application/json;charset=UTF-8');
        if ($method == 'POST') {
            $headers[] = 'Content-Length: ' . strlen($data);
        }
      
        // Set up our CURL request
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, "mimo:mimo");
        // Windows require this certificate
        $ca = dirname(__FILE__); 
        curl_setopt($ch, CURLOPT_CAINFO, $ca); // Set the location of the CA-bundle
        curl_setopt($ch, CURLOPT_CAINFO, $ca . '/cacert.pem'); // Set the location of the CA-bundle
        // Initiate request
        $rawData = curl_exec($ch);
     
        // If HTTP response wasn't 200,
        // log it as an error!
         $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         
        if ($code !== 200 && $code!==201 ) {
        	 $err =curl_error($ch);
            return array(
                'Success' => false,
                'Message' => "Request failed. Server responded with: {$code} "
            );
        } 

        // All done with CURL
        curl_close($ch);

        // Otherwise, assume we got some
        // sort of a response
        return json_decode($rawData, true);
    }

    /**
     * @param string $token oauth token
     */
    public function setToken($token)
    {
        $this->accessToken = $token;
    }

    /**
     * @return string oauth token
     */
    public function getToken()
    {
        return $this->accessToken;
    }

}
