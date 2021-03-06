<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Template Table class
 */
class EmailschedulerTableTemplate extends YireoTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase $db
	 */
	public function __construct(& $db)
	{
		// Set the required fields
		$this->_required = array(
			'label',
			'body',
		);

		// Call the constructor
		parent::__construct('#__emailscheduler_templates', 'id', $db);
	}
}
