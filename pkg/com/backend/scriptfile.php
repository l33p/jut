<?php
defined('_JEXEC') or die;

/*
 * jut! Installer Script
 *
 */

class com_jutInstallerScript extends JObject
{
	function __construct() {}
	
	/*
	 * Runs before installation
	 */
	function preflight($action, &$installer)
	{
		switch ($action)
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
	function postflight($action, &$installer)
	{
		// ...
	}
	
	function install(&$inst) {
		return true;
	}
	
	function uninstall(&$inst) {
		return true;
	}
	
	function update(&$inst) {
		return $this->install($inst);
	}
}

