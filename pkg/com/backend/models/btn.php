<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');


class UbarModelBtn extends UbarModel
{
	function UbarModelBtn()
	{
		parent::__construct();
		
		// Set button ID
		$ids	= JRequest::getVar('bid', array(0), '', 'array');
		$this->setId((int) $ids[0]);
	}
	
	function setId($id = 0) {
		$this->set('btn.id', $id);
		$this->set('btn.obj', false);
	}
	
	function &getButton()
	{
		if (!$this->get('btn.obj'))
		{
			$btn	= & $this->getTable();
			
			// Load button
			$id	= $this->get('btn.id', 0);
			if ($id > 0 && !$btn->load($id)) {
				JError::raiseError('', $btn->getError());
			}
			
			// Set type
			if ($type = JRequest::getWord('type')) {
				$btn->setParam('type', $type);
			}
			
			// Component URL parameters
			if ($uv = JRequest::getVar('url')) {
				$btn->setComponentUrl($uv);
			}
			
			$this->set('btn.obj', $btn);
		}
		
		$ref	= & $this->get('btn.obj');
		return $ref;
	}
	
	// Installed modules
	// See "com_modules/controller.php"
	function &getModuleList()
	{
		if (!isset($this->_modList))
		{
			// Close tree item
			if ($this->getState('tree', 'component', 'word') != 'module')
			{
				$this->_modList	= false;
				return $this->_modList;
			}
			
			// Module list
			$this->_modList	= array();
			$lang	= & JFactory::getLanguage();
			jimport('joomla.filesystem.folder');
			$dirs	= JFolder::folders(JPATH_ROOT.DS.'modules');
			foreach ($dirs as $d)
			{
				if (substr($d, 0, 4) == 'mod_')
				{
					$files 		= JFolder::files(JPATH_ROOT.DS.'modules'.DS.$d, '^([_A-Za-z0-9]*)\.xml$');
					$mod		= new JObject();
					$mod->file 	= $files[0];
					$mod->path 	= JPATH_ROOT.DS.'modules'.DS.$d;
					$mod->module	= str_replace('.xml', '', $files[0]);
					$this->_modList[]	= $mod;
					$lang->load($mod->module, JPATH_ROOT);
				}
			}
			
			// Module names
			if (count($this->_modList))
			{
				require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_modules'.DS.'helpers'.DS.'xml.php');
				ModulesHelperXML::parseXMLModuleFile($this->_modList);
				
				$n	= count($this->_modList);
				for ($i = 0; $i < $n; $i++) {
					$this->_modList[$i]->name	= JText::_(stripslashes($this->_modList[$i]->name));
				}
			}
			
			// Sort modules
			JArrayHelper::sortObjects($this->_modList, 'name');
		}
		
		return $this->_modList;
	}
	
	// Installed components
	// See "com_menus/helpers/helper.php"
	function &getComponentList()
	{
		if (!isset($this->_comList))
		{
			// Close tree item
			if ($this->getState('tree', 'component', 'word') != 'component')
			{
				$this->_comList	= false;
				return $this->_comList;
			}
			
			$db	= & JFactory::getDBO();
			$db->setQuery(
				'SELECT c.id, c.name, c.link, c.option '.
				'FROM #__components AS c '.
				'WHERE c.link <> "" AND c.parent = 0 AND c.enabled = 1 '.
				'ORDER BY c.name'
			);
			
			$this->_comList	= (array) $db->loadObjectList();
			
			if (count($this->_comList))
			{
				$lang	= & JFactory::getLanguage();
				foreach ($this->_comList as $c)
				{
					// Load language
					$lang->load($c->option, JPATH_ADMINISTRATOR);
					
					// Legacy components don't have "view"
					$c->legacy	= !is_dir(JPATH_SITE.DS.'components'.DS.$c->option.DS.'views');
				}
			}
		}
		
		return $this->_comList;
	}
	
	// Tree expansion
	// See "administrator/components/com_menus/models/item.php"
	function getTreeExpansion()
	{
		$x	= new JObject;
		$x->tree	= false;
		$x->option	= JRequest::getCmd('expand', '');
		
		// No option selected
		if (!strlen($x->option)) {
			return $x;
		}
		
		// Tree helper
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'tree.php');
		
		// Build tree
		$x->tree	= new uBarTree($x->option);
		
		return $x;
	}
	
	// Module parameters
	function &getModuleParams()
	{
		$btn	= & $this->getButton();
		$module	= JRequest::getCmd('module', $btn->getParam('_mod', null));
		if (!strlen($module)) {
			$params	= new JParameter('');
			return $params;
		}
		
		// Module table
		$table	= & JTable::getInstance('module');
		
		// Language
		$lang	= & JFactory::getLanguage();
		$lang->load($module, JPATH_ROOT);
		
		// XML file
		$xml	= JApplicationHelper::getPath('mod0_xml', $module);
		if ($data = JApplicationHelper::parseXMLInstallFile($xml)) {
			foreach($data as $key => $value) {
				$table->$key	= $value;
			}
		}
		
		// Return parameters
		$params = new JParameter($table->params, $xml, 'module');
		$params->loadArray($btn->getParameters());
		$params->set('_mod', $module);
		return $params;
	}
	
	// Component parameters
	function &getUrlParams()
	{
		// Default
		$params	= new JParameter('');
		
		// Parameters
		if ($state = & $this->getStateXML())
		{
			$btn	= & $this->getButton();
			$vars	= $btn->getComponentUrl();
			$sParams	= & $state->getElementByPath('url');
			$params->setXML($sParams);
			$params->loadArray($vars);
		}
		
		return $params;
	}
	
	/*
	 * Extension "State" information
	 * See "administrator/components/com_menus/models/item.php"
	 */
	function &getStateXML()
	{
		if (isset($this->_sxml)) {
			return $this->_sxml;
		}
		
		// Find metadata file path
		$this->_sxml	= false;
		$b	= & $this->getButton();
		
		// Check extension
		$vars	= $b->getComponentUrl();
		if (!isset($vars['option']) || !strlen($vars['option'])) {
			return $this->_sxml;
		}
		
		// Base folder
		$base	= JPATH_ROOT.DS.'components'.DS.$vars['option'];
		if (!is_dir($base)) {
			return $this->_sxml;
		}
		
		// Look in view
		if (isset($vars['view']))
		{
			// View folder
			$view	= $base.DS.'views'.DS.$vars['view'];
			
			// Look in layout
			$l	= isset($vars['layout']) ? $vars['layout'] : 'default';
			$xmlLayout	= $view.DS.'tmpl'.DS.$l.'.xml';
			if (file_exists($xmlLayout)) {
				$xmlPath	= $xmlLayout;
			}
			
			// Metadata not in layout
			else
			{
				$xmlView	= $view.DS.'metadata.xml';
				if (file_exists($xmlView)) {
					$xmlPath	= $xmlView;
				}
			}
		}
		
		// Default metadata file
		else {
			$xmlPath = $base.DS.'metadata.xml';
		}
		
		// Default XML file
		if (!file_exists($xmlPath)) {
			$xmlPath	= JApplicationHelper::getPath('com_xml', $vars['option']);
		}
		
		// Final check
		if (!file_exists($xmlPath)) {
			return $this->_sxml;
		}
		
		// Load file
		$parser	= & JFactory::getXMLParser('Simple');
		if (!$parser->loadFile($xmlPath)) {
			return $this->_sxml;
		}
		
		// "no options"
		$menu	= & $parser->document->getElementByPath('menu');
		if (is_a($menu, 'JSimpleXMLElement') && $menu->attributes('options') == 'none') {
			$xml	= & $menu->getElementByPath('state');
		} else {
			$xml	= & $parser->document->getElementByPath('state');
		}
		if (!$xml) {
			return $this->_sxml;
		}
		
		// "switch"
		if ($switch = $xml->attributes('switch'))
		{
			$default	= $xml->attributes('default');
			$switchVal	= isset($vars[$switch]) ? $vars[$switch] : 'default';
			$found		= false;
			
			// Handle "switch"
			foreach ($xml->children() as $child)
			{
				if ($child->name() == $switchVal)
				{
					$found	= true;
					$xml	= & $child;
					break;
				}
			}
			
			// Use default if nothing found
			if (!$found)
			{
				foreach ($xml->children() as $child)
				{
					if ($child->name() == $default) {
						$xml	= & $child;
						break;
					}
				}
			}
		}
		
		// "include" parameters
		$children	= $xml->children();
		if (count($children) == 1)
		{
			if ($children[0]->name() == 'include')
			{
				$tags	= array();
				$source	= $children[0]->attributes('source');
				$path	= $children[0]->attributes('path');
				
				preg_match_all( "/{([A-Za-z\-_]+)}/", $source, $tags);
				if (isset($tags[1])) {
					for ($i = 0; $i < count($tags[1]); $i++) {
						$source	= str_replace($tags[0][$i], @$vars[$tags[1][$i]], $source);
					}
				}
				
				// Load source XML file
				if (file_exists(JPATH_ROOT.$source))
				{
					if ($parser->loadFile(JPATH_ROOT.$source))
					{
						if ($state = & $parser->document->getElementByPath($path)) {
							$xml	= & $state;
						}
					}
				}
			}
		}
		
		// "switch" (new)
		if ($switch = $xml->attributes('switch'))
		{
			$default	= $xml->attributes('default');
			$switchVal	= ($vars[$switch]) ? $vars[$switch] : 'default';
			$found		= false;
			
			foreach ($xml->children() as $child)
			{
				if ($child->name() == $switchVal)
				{
					$xml	= & $child;
					$found	= true;
					break;
				}
			}
			
			if (!$found)
			{
				foreach ($xml->children() as $child)
				{
					if ($child->name() == $default)
					{
						$xml	= & $child;
						break;
					}
				}
			}
		}
		
		// Return "state" element
		if (is_a($xml, 'JSimpleXMLElement')) {
			$this->_sxml	= & $xml;
		} else {
			$this->_sxml	= false;
		}
		return $this->_sxml;
	}
	
	function save()
	{
		// Get button
		$btn	= & $this->getButton();
		$id		= JRequest::getInt('id');
		if ($id > 0 && !$btn->load($id)) {
			return $this->setError($btn->getError());
		}
		
		// Button settings
		$data	= JRequest::get('post');
		$data['published']	= isset($data['published']) ? 1 : 0;
		$data['params']['_dname']	= isset($data['params']['_dname']) ? 1 : 0;
		
		// Save button
	    if (!$btn->save($data, '', array('id'))) {
			return $this->setError($btn->getError());
	    }
		
		return true;
	}
	
	function remove($IDs)
	{
		$total	= count($IDs);
		$table	= & $this->getTable();
		
		// Delete buttons
		if (count($IDs)) {
			foreach($IDs as $id) {
				if (!$table->delete($id)) {
					return $this->setError($table->getError());
				}
			}						
		}
		
		// Return number of buttons
		return $total;
	}
	
	function publish($IDs, $publish = 0)
	{
		$total	= count($IDs);
		$table	= & $this->getTable();
		
		// Enable buttons
		if (!$table->publish($IDs, $publish)) {
			return $this->setError($table->getError());
		}
		
		// Return number of buttons
		return $total;
	}
	
	function orderItem($id, $to)
	{
		// Check ID
		$id	= (int) $id;
		if (!$id) {
			return $this->setError('Invalid ID');
		}
		
		// Move
		$table	= & $this->getTable();
		$table->load( $id );
		$table->move( $to );
		
		// Save
	    if (!$table->store()) {
			return $this->setError($table->getError());
	    }
		
		return true;
	}
	
	function reorder($IDs, $orders)
	{
		$total	= count($IDs);
		$table	= & $this->getTable();
		
		// update ordering values
		for($i = 0; $i < $total; $i++)
		{
			$table->load($IDs[$i]);
			
			if ($table->ordering != $orders[$i]) {
				$table->ordering	= $orders[$i];
				if (!$table->store()) {
					return $this->setError($table->getError());
				}
			}
		}
		
		// Sort
		$table->reorder();
		
		return true;
	}
	
	function access($id, $access)
	{
		// Check ID
		$id	= (int) $id;
		if (!$id) {
			return $this->setError('Invalid ID');
		}
		
		// Save access
		$table	= & $this->getTable();
		$table->load( $id );
		$table->access	= $access;
		
		// Save
	    if (!$table->store()) {
			return $this->setError($table->getError());
	    }
		
		return true;
	}
}

