<?php
defined('_JEXEC') or die;

class JutControllerConfig extends JutController
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		// Map tasks
		$this->registerTask('apply', 'save');
	}
	
	function save()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Save configuration
		$config	= $this->getModel('Config');
		$msg	= $config->save() ? JText::_('SUCCESSFULLY SAVED') : $config->getError();
		
		// Redirect
		$rdir	= $this->getTask() == 'apply' ? INDEX .'&view=config' : INDEX;
		$this->setRedirect($rdir, $msg);
	}
	
	function cancel() {
		parent::cancel(index .'&view=cpanel');
	}
}

