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
	public $language;
	/** @var string|void */
	public $title;
	/** @var string */
	public $body;

	/**
	 * Message constructor.
	 *
	 * @param             $language
	 * @param string      $body
	 * @param string|null $title
	 */
	public function __construct($language, string $body, ?string $title)
	{
		$this->language = $language;
		$this->body = $body;
		$this->title = $title;
	}
}
