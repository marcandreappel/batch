<?php
/**
 * BatchAbstract.php
 * @author      Marc-André Appel <marc-andre@hybride-conseil.fr>
 * @copyright   2018 Hybride Conseil
 * @license     http://opensource.org/licenses/MIT MIT
 * @link        https://www.sudimage.fr
 * @created     04/05/2018
 *
 * @github      https://github.com/MatchPint/batch
 */

namespace MarcAndreAppel\Batch;

use MarcAndreAppel\Batch\Exception\BatchException;

/**
 * Class BatchService
 * @brief Abstract class to model the basic specifications of Batch API.
 */
abstract class BatchAbstract extends BatchException
{

	/**
	 * Domain URL of the Batch API (custom, transactional and campaigns).
	 */
	const API_DOMAIN_URL = "https://api.batch.com";

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
	 * @brief BatchService constructor.
	 *
	 * @param string $apiKey     API Key corresponding to the Batch account to send request to.
	 * @param string $restKey    REST Key that provides the authorisation to access to the Batch API.
	 * @param string $apiVersion Version of the Batch API used.
	 */
	public function __construct($apiKey, $restKey, $apiVersion = '1.1')
	{
		if (empty($apiKey))
			throw new \InvalidArgumentException("You must provide a non-empty API Key");

		$this->apiKey = $apiKey;

		if (empty($restKey))
			throw new \InvalidArgumentException("You must provide a non-empty Rest Key");

		$this->restKey = $restKey;
		$this->baseURL = self::API_DOMAIN_URL . "/{$apiVersion}/{$this->apiKey}";
	}
}
