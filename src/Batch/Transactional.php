<?php
/**
 * Transactional.php
 * @author      Marc-André Appel <marc-andre@appel.fun>
 * @copyright   2018 Marc-André Appel
 * @license     http://opensource.org/licenses/LGPL-3.0 LGPL-3.0
 * @link        https://github.com/marcandreappel/batch
 * @created     04/05/2018
 */

namespace Batch;

use Batch\Exception\BatchException;

class Transactional extends BatchAbstract
{
	/** Path to send a push notification using transactional api */
	const TRANSACTIONAL_PATH = "transactional/send";

	/**
	 * @var array   Default options
	 */
	private static $DEFAULT_OPTIONAL_VALUES = array(
		"time_to_live"     => 172800,
		"gcm_collapse_key" => array("enabled" => false, "key" => "default"),
		"media"            => array(),
		"deeplink"         => "",
//		"custom_payload"   => "{}",     Paid only
		"landing"          => array()
	);

	public function __construct($apiKey, $restKey, $apiVersion = "1.1")
	{
		parent::__construct($apiKey, $restKey, $apiVersion);
		$this->baseURL = "{$this->baseURL}/" . self::TRANSACTIONAL_PATH;
	}

	/**
	 * @brief Verify the required params and send the notification.
	 *
	 * @param string   $pushIdentifier Identifier of the push notification.
	 * @param array    $recipients     Recipients of the notification.
	 * @param string[] $message        Message of the notification.
	 * @param array    $optionalFields Optional fields, overwriting default values.
	 */
	public function sendPush($pushIdentifier, $recipients, $message, $optionalFields = [])
	{

		$optionalFields = array_merge(self::$DEFAULT_OPTIONAL_VALUES, $optionalFields);

		/**
		 * @brief   Check push identifier
		 */
		if ( ! is_string($pushIdentifier)
			|| empty($pushIdentifier)
		) throw new BatchException("Incorrect push identifier field", 32);

		/**
		 * @brief   Check recipients
		 */
		if ( ! is_array($recipients)
			|| empty($recipients)
			|| ! (array_key_exists("custom_ids", $recipients)
				|| array_key_exists("tokens", $recipients)
				|| array_key_exists("install_ids", $recipients)
			)
		) throw new BatchException("Incorrect recipients field", 32);

		/**
		 * @brief   Check message
		 */
		if ( ! is_array($message)
			|| empty($message)
			|| ! (array_key_exists("title", $message)
				&& array_key_exists("body", $message)
			)
		) throw new BatchException("Incorrect message field", 32);

		/**
		 * @brief   Casting recipients to string
		 */
		array_walk_recursive($recipients, function (&$value) {
			$value = (string) $value;
		});

		$this->sendVerified($pushIdentifier, $recipients, $message, $optionalFields);
	}

	/**
	 * @brief Send information to batch to create a push notification.
	 * @link  https://batch.com/doc/api/transactional.html
	 *
	 * @param string   $pushIdentifier Identifier of the push notification.
	 * @param array    $recipients     Recipients of the notification.
	 * @param string[] $message        Message of the notification.
	 * @param array    $optionalFields Optional fields, overwriting default values.
	 */
	protected function sendVerified($pushIdentifier, $recipients, $message, $optionalFields)
	{
		$curl                         = curl_init();
		$opts                         = array();
		$opts[CURLOPT_RETURNTRANSFER] = true;
		$opts[CURLOPT_HTTP_VERSION]   = CURL_HTTP_VERSION_1_1;

		/** Method and URL */
		$opts[CURLOPT_POST] = true;
		$opts[CURLOPT_URL]  = $this->baseURL;

		/** Body of the request */
		$opts[CURLOPT_POSTFIELDS] = json_encode(array(
			"group_id"         => $pushIdentifier,
			"recipients"       => $recipients,
			"message"          => $message,
			"priority"         => $optionalFields["priority"],
			"time_to_live"     => $optionalFields["time_to_live"],
			"gcm_collapse_key" => $optionalFields["gcm_collapse_key"],
			"deeplink"         => $optionalFields["deeplink"],
//			"custom_payload"   => $optionalFields["custom_payload"],
			"media"            => $optionalFields["media"]
		));

		/** Authorization headers */
		$headers = [
			"Content-Type: application/json",
			"X-Authorization: {$this->restKey}"
		];
		curl_setopt_array($curl, $opts);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		if ($result = curl_exec($curl)) {
			$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if ($httpStatus >= 400) {
				throw BatchException::createFromResponseBody(json_decode($result, true));
			}
		} else {
			$error = curl_error($curl);
			throw new \RuntimeException("Error in Batch cURL call: $error");
		}
	}
}
