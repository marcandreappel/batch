<?php
/**
 * BatchAbstract.php
 * @author      Marc-André Appel <marc-andre@appel.fun>
 * @copyright   2018 Marc-André Appel
 * @license     http://opensource.org/licenses/LGPL-3.0 LGPL-3.0
 * @link        https://github.com/marcandreappel/batch
 * @created     04/05/2018
 *
 * @github      https://github.com/MatchPint/batch
 */

namespace Batch;

use Batch\Data\Message;
use Batch\Exception\BatchException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class BatchService
 * @brief Abstract class to model the basic specifications of Batch API.
 */
abstract class BatchAbstract extends BatchException
{
	const TRANSACTIONAL_PATH = "transactional/send";
	const CUSTOM_DATA_PATH = "data/users";
	const CAMPAIGN_PATH = "campaigns/create";

	/**
	 * Domain URL of the Batch API (custom, transactional and campaigns).
	 */
	const HTTPS_API_BATCH_COM = "https://api.batch.com";

	/**
	 * @var string $apiKey Batch API Key. Identify to which account the Request should be sent.
	 */
	protected $apiKey;

	/**
	 * @var string $restKey Batch REST Key. Grants the access to the API.
	 */
	protected $restKey;

	/**
	 * @var string $baseURL Base URL
	 */
	protected $baseURL;

	/**
	 * @var array $config Parameters to set
	 */
	protected $config;

	/**
	 * @brief BatchService constructor.
	 *
	 * @param string $apiKey     API Key corresponding to the Batch account to send request to.
	 * @param string $restKey    REST Key that provides the authorisation to access to the Batch API.
	 * @param string $apiVersion Version of the Batch API used.
	 * @param string $apiPath    Path to the Batch API endpoint
	 */
	public function __construct(string $apiKey, string $restKey, string $apiVersion, string $apiPath)
	{
		if (empty($apiKey))
		{
			throw new \InvalidArgumentException("You must provide a non-empty API Key");
		}
		$this->apiKey = $apiKey;

		if (empty($restKey))
		{
			throw new \InvalidArgumentException("You must provide a non-empty Rest Key");
		}
		$this->restKey = $restKey;
		$this->baseURL = self::HTTPS_API_BATCH_COM . "/{$apiVersion}/{$this->apiKey}/{$apiPath}";
	}

	/**
	 * @brief Helper function to add values to the configuration array
	 *
	 * @param      $key
	 * @param bool $value
	 */
	public function addConfig($key, $value = false)
	{
		if (is_array($key))
		{
			$_config      = $this->config;
			$this->config = array_merge($_config, $key);
		}
		else
		{
			$this->config[$key] = $value;
		}
	}

	/**
	 * @brief Helper function to create the message array into the configuration
	 *
	 * @param string|null $language
	 * @param string      $body
	 * @param string|null $title
	 */
	public function setMessage(?string $language, string $body, ?string $title = null): void
	{
		$message  = new Message($body, $title, $language);

		if (!is_null($language)) {
			$messages = (array_key_exists('messages', $this->config)
				&& is_array($this->config['messages'])) ? $this->config['messages'] : array();
			$messages[]               = $message;
			$this->config['messages'] = $messages;
		}
		else
		{
			$this->config['message'] = $message;
		}
	}

	/**
	 * @brief  Send the push notification
	 * @return string
	 */
	public function send()
	{
		if (!strlen($_errors = $this->checkConfig()) == 0)
		{
			$error = new BatchException("Faulty configuration: \n$_errors");

			return $error->getMessage();
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
		}
		catch (GuzzleException $exception)
		{
			$error = new BatchException($exception->getMessage());

			return $error->getMessage();
		}
	}

	/**
	 * @return string
	 */
	abstract public function checkConfig();
}
