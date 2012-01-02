<?php
defined('_JEXEC') or die;

// Plugin libraries
jimport('joomla.plugin.plugin');

// Defines
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jut'.DS.'defines.php');

class plgSystemJut extends JPlugin
{
	private $doc	= null;
	private $script	= ' ';
	
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		
		// test test
		$this->doc	= JFactory::getDocument();
	}
	
	public function onAfterInitialise()
	{
		// ...
	}
	
	/*
	 * 
	 */
	public function onAfterRoute()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return;
		}
		
		// No menus
		/*$ms	= (int) $this->params->get('menus', 1);
		if ($ms == 0) {
			return $this->isIncluded(false);
		}
		
		// Check Itemid
		if ($ms == 2)
		{
			$id	= JRequest::getInt('Itemid', 0);
			$mis	= (array) $this->params->get('mis', array());
			if (!in_array($id, $mis)) {
				return $this->isIncluded(false);
			}
		}*/
	}
	
	/*
	 * 
	 */
	public function onAfterDispatch()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return;
		}
		
		
	}
	
	/*
	 * Returns list of buttons
	 */
	function getButtons()
	{
		return array();
		
		// Get buttons
		$db	= & JFactory::getDBO();
		$user	= & JFactory::getUser();
		$access	= (int) $user->get('aid', 0);
		$db->setQuery(
			'SELECT * FROM #__ubar_btn'.
			' WHERE access <= '. $access .
			' AND published = 1'.
			' ORDER BY ordering');
		$list	= (array) $db->loadObjectList();
		
		// Database error
		if ($db->getErrorNum())
		{
			global $mainframe;
			$msg	= 'uBar (db): '. $db->getErrorMsg();
			$mainframe->enqueueMessage($msg, 'notice');
			return false;
		}
		
		// Empty list
		if (empty($list)) {
			return false;
		}
		
		// Return buttons
		for ($i = 0, $n = count($list); $i < $n; $i++) {
			$list[$i]->params	= new JParameter($list[$i]->params);
		}
		
		return $list;
	}
	
	/*
	 * Shortcut to include a script
	 */
	function insertScript($file = null, $js = null)
	{
		// Javascript file
		if ($file) {
			self::$doc->addScript(JURI::root() . $file .'?'. JUT_VERSION_INC);
		}
		
		// Javascript statement
		if ($js) {
			self::$script	.= $js;
		}
	}
	
	/*
	 * Shortcut to disable
	 */
	public function disable() {
		return $this->isIncluded(false);
	}
	
	/*
	 * Determines if bar should be included
	 */
	public function isIncluded($new = null)
	{
		static $inc;
		
		if (is_null($inc))
		{
			// Client check
			if (!JFactory::getApplication()->isSite()) {
				$inc	= false;
				return $inc;
			}
			
			// Check that we're not offline
			// ...
			
			// Force display
			$bar	= JRequest::getInt('ubar', -1);
			if ($bar == 0 || $bar == 1) {
				$inc	= (bool) $bar;
				return $inc;
			}
			
			// Check template type
			$tmpl	= JRequest::getVar('tmpl', 'index');
			if ($tmpl != 'index') {
				$inc	= false;
				return $inc;
			}
			
			// Check format
			$format	= JRequest::getCmd('format', 'html');
			if ($format != 'html') {
				$inc	= false;
				return $inc;
			}
			
			// Access level
			// ...
			
			$inc	= true;
		}
		
		if (is_bool($new)) {
			$inc	= $new;
		}
		
		return $inc;
	}
}

