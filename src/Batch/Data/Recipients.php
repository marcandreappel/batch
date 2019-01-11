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
	public $recipients;

	private $types = array('tokens' => 'tokens', 'customIDs' => 'custom_ids', 'installIDs' => 'install_ids');
	/**
	 * Recipients constructor.
	 *
	 * @param string $type
	 * @param array  $recipients
	 */
	public function __construct(string $type, array $recipients)
	{
		if (array_key_exists($type, $this->types) && is_array($recipients))
		{
			$this->recipients = array($this->types[$type] => $recipients);
		}
		else
		{
			throw new BatchException("Wrong class construction.");
		}
	}
}
