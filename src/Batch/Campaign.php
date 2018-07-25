<?php
/**
 * Campaign.php
 * @author      Marc-André Appel <support@sudimage.fr>
 * @copyright   2018 Sudimage Communication
 * @license     http://opensource.org/licenses/MIT MIT
 * @link        https://www.sudimage.fr
 * @created     04/05/2018
 */

namespace MarcAndreAppel\Batch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Campaign extends BatchAbstract
{
	const CUSTOM_DATA_PATH = "campaigns/create";

	public function __construct($apiKey, $restKey, $apiVersion = "1.1")
	{
		parent::__construct($apiKey, $restKey, $apiVersion);
		$this->baseURL = "{$this->baseURL}/" . self::CUSTOM_DATA_PATH;
	}

	/**
	 * @param $title
	 * @param $message
	 *
	 * @return mixed|\Psr\Http\Message\ResponseInterface
	 */
	public function send($title, $message) {
		$client = new Client();
		try
		{
			return $client->request(
				"POST", $this->baseURL,
				array(
					"headers" => array(
						"Content-Type"    => "application/json",
						"X-Authorization" => $this->restKey
					),
					"json"    => array(
						"name"      => $title,
						"live"      => true,
						"push_time" => "now",
						"messages"  => array(
							array(
								"language" => "fr",
								"body"     => $message
							)
						)
					)
				));
		} catch (GuzzleException $exception) {
			die($exception->getMessage());
		}
	}
}