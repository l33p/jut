<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');


class JutModel extends JModel
{
	function JutModel()
	{
		parent::__construct();
		
		
	}
	
	function display()
	{
		
		
		parent::display();
	}
	
	// Plugin parameters
	function &getParams($rTable = false)
	{
		static $params, $tbl;
		
		// Parameters
		if (is_null($params))
		{
			// Find table
			$tbl	= JTable::getInstance('extension');
			$id	= $tbl->find(array('element' => 'com_jut'));
			if ($id == null) {
				return $this->setError('Extension ID not found');
			}
			
			// Load data
			if (!$tbl->load(array($tbl->getKeyName() => $id), true)) {
				return $tbl->setError($tbl->getError());
			}
			
			// Load from database
			//$db	= JFactory::getDBO();
			//$query	= $db->getQuery(true);
			//$query->select('*');
			//$query->from('#__extensions');
			//$query->where('element = '. $db->quote('com_jut'));
			//$db->setQuery((string) $query);
			//$tbl	= $db->loadObject();
			
			// Create parameters object
			$params	= new JParameter($tbl->params);
			$params->loadSetupFile(JPATH_COMPONENT.DS.'config.xml');
			$params->addElementPath(JPATH_COMPONENT.DS.'classes'.DS.'parameter');
		}
		
		// Return table
		if ($rTable) {
			return $tbl;
		}
		
		// Return params
		return $params;
	}
	
	function setError($msg)
	{
		$e	= new JException($msg);
		$this->setError($e);
		
		return false;
	}
	
	function getState($request, $def = null, $type = 'none') {
		//$app	= & JApplication::getInstance('administrator');
		//return $app->getUserStateFromRequest('jut.'. $request, $request, $def, $type);
		return JFactory::getApplication()->getUserStateFromRequest('jut.'. $request, $request, $def, $type);
	}
	function setState($var, $value) {
		//$app	= & JApplication::getInstance('administrator');
		//return $app->setUserState('jut.'. $var, $value);
		return JFactory::getApplication()->setUserState('jut.'. $var, $value);
	}
}

