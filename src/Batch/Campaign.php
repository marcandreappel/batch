<?php
/**
 * Campaign.php
 * @author      Marc-André Appel <support@sudimage.fr>
 * @copyright   2018 Sudimage Communication
 * @license     http://opensource.org/licenses/MIT MIT
 * @link        https://www.sudimage.fr
 * @created     04/05/2018
 */

namespace Batch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Batch\Exception\BatchException;

class Campaign extends BatchAbstract
{
	const CUSTOM_DATA_PATH = "campaigns/create";

	/** @var array Default values */
	private $config = array(
		"live" => true,
		"push_time" => "now",
	);

	/**
	 * Campaign constructor.
	 *
	 * @param        $apiKey
	 * @param        $restKey
	 * @param string $apiVersion
	 */
	public function __construct($apiKey, $restKey, $apiVersion = "1.1")
	{
		parent::__construct($apiKey, $restKey, $apiVersion);
		$this->baseURL .= "/" . self::CUSTOM_DATA_PATH;
	}

	/**
	 * @return BatchException|mixed|\Psr\Http\Message\ResponseInterface
	 */
	public function send()
	{
		if (!strlen($_errors = $this->checkConfig()) == 0) {
			return new BatchException("Faulty configuration: \n$_errors");
		}

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
					"json" => $this->config
				));
		} catch (GuzzleException $exception) {
			return new BatchException($exception->getMessage());
		}
	}

	/**
	 * @return string
	 */
	public function checkConfig()
	{
		$_conf = $this->config;
		$_errors = array();
		if (!key_exists('name', $_conf) || strlen($_conf['name']) < 3) {
			$_errors[] = "A correct campaign name is required";
		}
		if (!key_exists('messages', $_conf) || !is_array($_conf['messages']) || empty($_conf['messages'])) {
			$_errors[] = "A localized message is required";
		}
		$error = (!empty($_errors)) ? implode(" \n", $_errors) : '';
		return $error;
	}
}