<?php
/**
 * Message.php
 * @author      Marc-André Appel <marc-andre@hybride-conseil.fr>
 * @copyright   2019 Hybride Conseil
 * @license     http://opensource.org/licenses/MIT MIT
 * @link        https://www.hybride-conseil.fr
 * @created     10/01/2019
 */

namespace Batch\Data;

class Message {

	/** @var string */
	public $body;
	/** @var string|null */
	public $title = null;
	/** @var string */
	public $language;

	/**
	 * Message constructor.
	 *
	 * @param             $language
	 * @param string      $body
	 * @param string|null $title
	 */
	public function __construct(string $body, ?string $title, ?string $language)
	{
		$this->body = $body;
		$this->title = $title;
		if (is_null($language))
		{
			unset($this->language);
		}
		else
		{
			$this->language = $language;
		}
	}
}
