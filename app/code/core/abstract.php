<?php
namespace MCPI;

/**
 * Core Abstract
 *  - Contains methods useful in all classes
 */
class Core_Abstract
{

    /**
     * Return budget menu singleton
     */
    static function getBudgetMenu()
    {
        return Budget_Model_Menu::instance();
    }

    /**
     * Return date filter singleton
     */
    static function getDateFilter()
    {
        return Core_Model_Datefilter::instance();
    }

    /**
     * Return message singleton
     */
    static function getMessage()
    {
        return Core_Model_Message::instance();
    }

        /* Message Helpers */
        /*******************/

        // show a message
        static function log($message, $type=false)
        {
            self::getMessage()->_log($message, $type);
        }

        // show an error message
        static function error($message, $die=false)
        {
            self::getMessage()->_error($message, $die);
        }

    /**
     * Return request singleton
     */
    static function getRequest()
    {
        return Core_Model_Request::instance();
    }

    /**
     * Return response singleton
     */
    static function getResponse()
    {
        return Core_Model_Response::instance();
    }

    /**
     * Return session singleton
     */
    static function getSession($key=null)
    {
        return Core_Model_Session::instance($key);
    }

	/**
	 * Get a new curl handle with pre-set defaults
	 */
	static function getCurl($url)
	{
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 0,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_FOLLOWLOCATION => true,
		]);
		return $curl;
	}

	/**
	 * GET JSON data from an endpoint
	 */
	static function getJSON($url, $data, $headers=[]) {

		if (!empty($data)) { 
			$url .= "?" . http_build_query($data);
		}

		$curl = self::getCurl($url);
		$payload = json_encode($data);
		curl_setopt( $curl, CURLOPT_POST, false );
		curl_setopt( $curl, CURLOPT_HEADER, true );
		curl_setopt( $curl, CURLOPT_HTTPHEADER,
			array_merge(
				[
					'Accept: application/json',
				],
				$headers
			)
		);
		$response = curl_exec($curl);
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		curl_close($curl);

		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		$body = json_decode($body, true);

		if ( empty( $body ) ) {
			echo("<pre>".print_r($header,true)."</pre>");
			die("Curl failed - empty response");
		}

		return $body;
	}

	/**
	 * Post data as JSON to an endpoint
	 */
	static function postJSON($url, $data, $headers=[]) {

		$curl = self::getCurl($url);
		$payload = json_encode($data);
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $curl, CURLOPT_HEADER, true );
		curl_setopt( $curl, CURLOPT_HTTPHEADER,
			array_merge(
				[
					'Content-Type:application/json',
					'Accept: application/json',
				],
				$headers
			)
		);
		$response = curl_exec($curl);
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		curl_close($curl);

		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		$body = json_decode($body, true);

		if ( empty( $body ) ) {
			echo("<pre>".print_r($header,true)."</pre>");
			die("Curl failed - empty response");
		}

		return $body;
	}
}
