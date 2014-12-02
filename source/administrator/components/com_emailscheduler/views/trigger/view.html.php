<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class
 */
class EmailschedulerViewTrigger extends YireoViewForm
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
        YireoHelper::bootstrap();

        $this->fetchItem();

        $data = (array)$this->getModel()->getData();
        $form = $this->getModel()->getForm();
        
        JPluginHelper::importPlugin('emailscheduler');
        $dispatcher = JEventDispatcher::getInstance();
        $results = $dispatcher->trigger('onEmailschedulerTriggerPrepareForm', array(&$form, &$data));

        $form->bind(array('item' => $data, 'actions' => $data['actions']));
        $this->assignRef('form', $form);

		parent::display($tpl);
	}
}
