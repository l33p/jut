<?php
defined('JPATH_BASE') or die;

/**
 * Renders a menu item element
 *
 * JParameter extension to get a menu item list
 * similar to the one found when editing a module.
 */
class JElementMenuItems extends JElement
{
	var	$_name = 'MenuItems';
	
	// See "administrator/components/com_modules/controller.php"
	function fetchElement($name, $value, &$node, $control)
	{
		// Get menu list
		$sels	= JHTML::_('menu.linkoptions');
		
		// Return selections
		return JHTML::_('select.genericlist', $sels, $control.'['.$name.'][]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $value, $control.$name);
	}
}

