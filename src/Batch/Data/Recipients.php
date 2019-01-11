<?php
/**
 * Recipients.php
 * @author      Marc-André Appel <marc-andre@hybride-conseil.fr>
 * @copyright   2019 Hybride Conseil
 * @license     http://opensource.org/licenses/MIT MIT
 * @link        https://www.hybride-conseil.fr
 * @created     10/01/2019
 */

namespace Batch\Data;

use Batch\Exception\BatchException;

class Recipients
{
	/** @var array|void */
	private $tokens = null;
	/** @var array|void */
	private $customIDs = null;
	/** @var array|void */
	private $installIDs = null;

	/**
	 * Recipients constructor.
	 *
	 * @param string $type
	 * @param array  $recipients
	 */
	public function __construct(string $type, array $recipients)
	{
		if (property_exists(self::class, $type) && is_array($recipients))
		{
			$this->$type = $recipients;
		}
		else
		{
			throw new BatchException("Wrong class construction.");
		}
	}
}
