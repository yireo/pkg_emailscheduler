<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Emailscheduler Controller
 */
class EmailschedulerController extends YireoController
{
	/**
	 * Constructor
	 *
	 * @package Emailscheduler
	 */
	public function __construct()
	{
		$this->_default_view = 'home';
		$this->_allow_raw[] = 'body_html';
		$this->_allow_raw[] = 'body';

		parent::__construct();
	}

	/**
	 * Method to send a specific mail
	 */
	public function send()
	{
		// Get the ID-list
		$cid = $this->getIds();

		if (count($cid) < 1)
		{
			throw new Exception(JText::_('LIB_YIREO_CONTROLLER_ITEM_SELECT'), 500);
		}

		/** @var EmailschedulerModelEmail $model */
		$model = $this->_loadModel();

		foreach ($cid as $id)
		{
			$model->setId($id);
			$model->send();
		}

		// Redirect
		$link = 'index.php?option=com_emailscheduler&view=emails';
		$msg = JText::_('COM_EMAILSCHEDULER_CONTROLLER_PENDING_SENT');
		$this->setRedirect($link, $msg);
	}

	/**
	 * Method to run SQL-update queries
	 */
	public function updateQueries()
	{
		// Run the update-queries
		require_once JPATH_COMPONENT . '/helpers/update.php';
		EmailschedulerUpdate::runUpdateQueries();

		// Redirect
		$link = 'index.php?option=com_emailscheduler&view=home';
		$msg = JText::_('LIB_YIREO_CONTROLLER_DB_UPGRADED');
		$this->setRedirect($link, $msg);
	}

	/**
	 * Method to delete all sent emails
	 */
	public function deleteSent()
	{
		$db = JFactory::getDBO();

		// Delete all logs
		$db->setQuery('DELETE FROM #__emailscheduler_logs WHERE `email_id` IN (SELECT id FROM #__emailscheduler_emails WHERE `send_state`="sent")');
		$db->execute();

		// Delete all emails
		$db->setQuery('DELETE FROM #__emailscheduler_emails WHERE `send_state`="sent"');
		$db->execute();

		// Redirect
		$link = 'index.php?option=com_emailscheduler&view=emails';
		$msg = JText::_('COM_EMAILSCHEDULER_CONTROLLER_DELETE_SENT');
		$this->setRedirect($link, $msg);
	}

	/**
	 * Method to run an autocomplete action of a specified EmailScheduler plugin
	 */
	public function autocomplete()
	{
        ini_set('display_errors', 1);
        error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

		$plugin = $this->_app->input->getCmd('plugin');
		$like = $this->_app->input->getString('like');

		JPluginHelper::importPlugin('emailscheduler');
		$event = 'onEmailscheduler' . ucfirst($plugin) . 'Search';
		$dispatcher = JEventDispatcher::getInstance();

        try {
    		$matches = $dispatcher->trigger($event, array(&$like));
        } catch(Exception $e)
        {
            echo $e->getMessage();exit;
        }

		$results = array();

		foreach ($matches[0] as $matchId => $matchLabel)
		{
			$results[] = array('value' => $matchId, 'text' => $matchLabel);
		}

		echo json_encode($results);
		$this->_app->close();
	}
}
