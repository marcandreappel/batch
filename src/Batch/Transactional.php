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

use Batch\Data\Recipients;

class Transactional extends BatchAbstract
{
	/**
	 * Transactional constructor.
	 *
	 * @param        $apiKey
	 * @param        $restKey
	 * @param string $apiVersion
	 */
	public function __construct($apiKey, $restKey, $apiVersion = "1.1")
	{
		parent::__construct($apiKey, $restKey, $apiVersion, parent::TRANSACTIONAL_PATH);
	}

	/**
	 * @return string
	 */
	public function checkConfig(): string
	{
		$config = $this->config;
		$errors = array();

		/** Check for the Group ID (name of the campaign) */
		if (!key_exists('group_id', $config) || strlen($config['group_id']) < 3)
		{
			$errors[] = "A correct group id is required";
		}
		if (!array_key_exists('message', $config) || empty($config['message']))
		{
			$errors[] = "A localized message is required";
		}
		if (!array_key_exists('recipients', $config)
			|| !is_object($config['recipients'])
			|| !(method_exists($config['recipients'], "custom_ids")
				|| method_exists($config['recipients'], "tokens")
				|| method_exists($config['recipients'], "install_ids")
			))
		{
			$errors[] = "Incorrect recipients field";
		}
		$error = (!empty($errors)) ? implode("\r\n", $errors) : '';

		return $error;
	}

	/**
	 * @brief Helper function to prepare the recipients for the transactional push
	 *
	 * @param string $type
	 * @param array  $recipients
	 *
	 * @return void
	 */
	public function setRecipients(string $type, array $recipients): void
	{
		$recipientsObject = new Recipients($type, $recipients);
		$this->config['recipients'] = $recipientsObject;
	}
}
