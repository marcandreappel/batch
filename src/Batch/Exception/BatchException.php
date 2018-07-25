<?php
/**
 * BatchException.php
 * @author      Marc-André Appel <marc-andre@hybride-conseil.fr>
 * @copyright   2018 Hybride Conseil
 * @license     http://opensource.org/licenses/MIT MIT
 * @link        https://www.sudimage.fr
 * @created     04/05/2018
 */

namespace MarcAndreAppel\Batch\Exception;

class BatchException extends \RuntimeException
{

	/**
	 * Error the API or REST key.
	 */
	const AUTHENTICATION_INVALID = 10;

	/**
	 * Error the API or REST key.
	 */
	const ROUTE_NOT_FOUND = 20;

	/**
	 * A parameter is missing in the request.
	 */
	const MISSING_PARAMETER = 30;

	/**
	 * A parameter doesn't have the right format.
	 */
	const MALFORMED_PARAMETER = 31;

	/**
	 * The body doesn't fetch a JSON format.
	 */
	const MALFORMED_JSON_BODY = 32;

	/**
	 * Internal error coming from Batch's servers.
	 */
	const SERVER_ERROR = 1;

	/**
	 * Batch's server are in maintenance.
	 */
	const MAINTENANCE_ERROR = 2;


	/**
	 * @brief Creates a BatchException from the response body of a failed Batch API request.
	 *
	 * @param array $responseBody Response body of a failed API request.
	 *
	 * @return BatchException
	 */
	static public function createFromResponseBody($responseBody)
	{
		// Format of response body for an error: {"code": integer, "type": string, "message": string}.
		return new self($responseBody['message'], $responseBody['code']);
	}
}
