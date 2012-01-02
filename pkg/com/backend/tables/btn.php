<?php
defined('_JEXEC') or die;

class TableBtn extends JTable
{
	var $id			= 0;
	var $name		= '';
	var $desc		= '';
	var $icon		= '';
	var $params		= '';
	var $misc		= '';
	var $access		= 0;
	var $ordering	= 0;
	var $published	= 0;
	
	function TableBtn(&$db) {
		parent::__construct('#__ubar_btn', 'id', $db);
	}
	
	function load($oid = null)
	{
		if (!parent::load($oid)) {
			return false;
		}
		
		// Parameters
		$this->loadParams();
		
		return true;
	}
	
	function bind($data, $ignore = array())
	{
		if (!parent::bind($data, $ignore)) {
			return false;
		}
		
		// Type parameter
		$this->loadParams();
		settype($data, 'array');
		switch ($this->getParam('type'))
		{
			// Custom menu item
			case 'menu':
			case 'user':
				if (isset($data['items']) && count($data['items'])) {
					$this->misc	= implode("\n", $data['items']);
				}
				break;
			
			// Component
			case 'component':
				if (isset($data['urlparams']) && count($data['urlparams'])) {
					$this->setComponentUrl($data['urlparams']);
				}
				break;
			
			// HTML
			case 'html':
				if (JRequest::checkToken() && $html = JRequest::getVar('misc')) {
					$this->misc	= JRequest::getString('misc', '', 'POST', JREQUEST_ALLOWRAW);
				}
				break;
		}
		
		return true;
	}
	
	function check()
	{
		// Check name
		$this->name	= JString::trim($this->name);
		if (empty($this->name)) {
			$this->setError(JText::sprintf('MISSING PARAM', JText::_('NAME')));
			return false;
		}
		
		// Parameters
		$this->loadParams($this->params);
		$this->params	= $this->_params->toString();
		
		// Check type
		$type	= trim($this->getParam('type'));
		if (!strlen($type)) {
			$this->setError(JText::sprintf('MISSING PARAM', JText::_('TYPE')));
			return false;
		}
		
		// Other checks
		$this->icon	= trim($this->icon);
		$this->desc	= JString::trim($this->desc);
		$this->access	= (int) $this->access;
		$this->ordering	= (int) $this->ordering;
		$this->published	= (int) $this->published;
		
		// Checks by type
		switch ($type)
		{
			
		}
		
		return true;
	}
	
	/*
	 * Parameters
	 */
	function getParam($name, $def = null) {
		$this->loadParams();
		return $this->_params->get($name, $def);
	}
	function getParameters() {
		$this->loadParams();
		return $this->_params->toArray();
	}
	function setParam($name, $value = null) {
		$this->loadParams();
		return $this->_params->set($name, $value);
	}
	function loadParams($params = null)
	{
		// Initialize params
		if (!isset($this->_params))
		{
			$this->_params	= new JParameter('');
			if (is_array($this->params)) {
				$this->_params->loadArray($this->params);
			} else {
				$this->_params->loadINI($this->params);
			}
		}
		
		// Load params array
		if (is_array($params)) {
			$this->_params->loadArray($params);
		}
	}
	
	// Admin edit url
	function getEditUrl()
	{
		$type	= $this->getParam('type');
		$url	= index .'&task=btn:edit&type='. $type;
		
		// Add URL parameters
		if ($type == 'component')
		{
			$uParams	= $this->getComponentUrl();
			
			if (count($uParams)) {
				foreach ($uParams as $k => $v) {
					$url	.= '&url['. $k .']='. $v;
				}
			}
		}
		
		return $url .'&bid[]='. $this->get('id');
	}
	
	/*
	 * Type specific functions
	 */
	
	// Menu item list
	function getMenuLinks()
	{
		// Performance check
		if (!JString::strlen($this->misc)) {
			return array();
		}
		
		// Get links
		$links	= @explode("\n", $this->misc);
		
		// Build links array
		$list	= array();
		foreach ($links as $link)
		{
			// Variables
			$data	= array();
			parse_str($link, $data);
			$obj	= new JObject();
			
			// Link details
			$obj->text		= urldecode( @$data['t'] );
			$obj->url		= urldecode( @$data['u'] );
			$obj->target	= @$data['x'];
			$obj->width		= (int) @$data['w'];
			$obj->height	= (int) @$data['h'];
			$list[]	= $obj;
		}
		
		return $list;
	}
	
	// Component URL parameters
	function setComponentUrl($uv = array())
	{
		if (isset($uv['option']))
		{
			$url	= 'index.php?option='. $uv['option'];
			foreach ($uv as $k => $v) {
				if ($k != 'option') {
					$url	.= '&'. $k .'='. $v;
				}
			}
			
			// Save URL
			$this->_url	= $uv;
			$this->misc	= $url;
			
			// Load language
			$lang	= & JFactory::getLanguage();
			$lang->load($uv['option'], JPATH_ADMINISTRATOR);
		}
	}
	function getComponentUrl($toString = false)
	{
		if (!isset($this->_url))
		{
			$this->_url	= array();
			
			// Get component URL
			if (strlen($this->misc) < 5) {
				return $toString ? $this->misc : $this->_url;
			}
			
			// Get URL parameters
			parse_str(substr($this->misc, 10), $this->_url);
			
			// Load language
			if (isset($this->_url['option']))
			{
				$lang	= & JFactory::getLanguage();
				$lang->load($this->_url['option'], JPATH_ADMINISTRATOR);
			}
		}
		
		return $toString ? $this->misc : $this->_url;
	}
	
	/*
	 * Button script for bar
	 */
	function includeScript()
	{
		// Get button type
		$type	= $this->getParam('type');
		
		// Base class
		if (!class_exists('uBarButtonClass')) {
			require_once(UBAR_BTNS.DS.'base.php');
		}
	}
}

