<?php

// in case this is called like standalone script - we need wordpress functions available
if ( ! function_exists( 'get_option' ) ) {
	// absolute path to wp installation root
	$wordpress_path = realpath ( dirname ( __FILE__ ) . '/../../../../' );
	require_once $wordpress_path . '/wp-load.php';
}

// init SweetCaptcha instance
$sweetcaptcha_instance = new Sweetcaptcha(
	get_option( 'sweetcaptcha_app_id' ), 
	get_option( 'sweetcaptcha_key' ), 
	get_option( 'sweetcaptcha_secret' ), 
	get_option( 'sweetcaptcha_public_url' )
);

/*
 * Do not change below here.
 */

/**
 * Handles remote negotiation with Sweetcaptcha.com.
 *
 * @version 1.0
 * @since December 14th, 2010
 * 
 */

if (isset($_POST['ajax']) and $method = $_POST['ajax']) {
	echo $sweetcaptcha_instance->$method(isset($_POST['params']) ? $_POST['params'] : array());
}

class Sweetcaptcha {
	
	private $appid;
	private $key;
	private $secret;
	private $path;
	
	const API_URL = 'http://www.sweetcaptcha.com/api.php';
	
	function __construct($appid, $key, $secret, $path) {
		$this->appid = $appid;
		$this->key = $key;
		$this->secret = $secret;
		$this->path = $path;
	}
	
	private function api($method, $params) {
		
		$basic = array(
			'method' => $method,
			'appid' => $this->appid,
			'key' => $this->key,
			'secret' => $this->secret,
			'path' => $this->path,
			'is_mobile' => preg_match('/mobile/i', $_SERVER['HTTP_USER_AGENT']) ? 'true' : 'false',
		);
		
		return $this->call(array_merge(isset($params[0]) ? $params[0] : $params, $basic));
	}
	
	private function call($params) {
		
		$ch = curl_init(self::API_URL);
		 curl_setopt($ch, CURLOPT_POST, 1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1); 
		 curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS 
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
		 curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		 curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
		 $response = curl_exec($ch);
		 return $response;	
		 			
	}
	
	public function __call($method, $params) {
		return $this->api($method, $params);
	}
}
