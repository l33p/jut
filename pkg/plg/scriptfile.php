<?php
defined('_JEXEC') or die;

/*
 * Installer Script
 *
 */
class plgsystemjutInstallerScript extends JObject
{
	function __construct() {}
	
	/*
	 * Runs before installation
	 */
	function preflight($act, &$inst)
	{
		switch ($act)
		{
			case 'install':
				break;
			
			case 'update':
				break;
		}
	}
	
	/*
	 * Runs after installation
	 */
	function postflight($act, &$inst)
	{
		// Get plugin id
		$tbl	= JTable::getInstance('Extension');
		$id	= $tbl->find(array('element' => 'jut', 'folder' => 'system'));
		if ($id == null) {
			return true;
		}
		
		// Load plugin
		if (!$tbl->load(array($tbl->getKeyName() => $id), true)) {
			return true;
		}
		
		// Enable plugin
		$tbl->enabled	= 1;
		if (!$tbl->store()) {
			return true;
		}
		
		return true;
	}
	
	/*
	 * Runs on first install
	 */
	function install(&$inst)
	{
		return true;
	}
	
	function uninstall(&$inst)
	{
	
	}
	
	/*
	 * Runs on update
	 */
	function update(&$inst) {
		return $this->install($inst);
	}
}

