<?php
defined('_JEXEC') or die;

// Import library dependencies
JHTML::_('behavior.mootools');

/*
 * MooTools helper for javascript effects
 */
class uBarMooTools extends JObject
{
	function editMenu($id, $links = null)
	{
		// Menu links
		$menu	= '';
		if (is_array($links) && count($links))
		{
			foreach ($links as $link)
			{
				$menu	.= 'uBarMenu.addItem('.
					'"'. addslashes($link->get('text')) .'",'.
					'"'. addslashes($link->get('url')) .'",'.
					'"'. $link->get('target') .'",'.
					$link->get('width', 0) .','.
					$link->get('height', 0) .',true'.
				');';
			}
		}
		
		// Add scripts
		uBarMooTools::js('edit-menu',
			'uBarMenu.siteRoot="'. JURI::root() .'";'.
			'uBarMenu.txtNew="'. JText::_('NEW', true) .'";'.
			'uBarMenu.txtEdit="'. JText::_('EDIT', true) .'";'.
			'uBarMenu.txtMissingText="'. addslashes(JText::sprintf('MISSING PARAM', JText::_('TEXT'))) .'";'.
			'uBarMenu.txtMissingUrl="'. addslashes(JText::sprintf('MISSING PARAM', 'URL')) .'";'.
			'uBarMenu.txtDelete="'. JText::_('VALIDDELETEITEMS', true) .'";'.
			'uBarMenu.init("'. $id .'");'. $menu
		);
		
		// Add styles
		uBarMooTools::css('edit-menu');
		
	}
	
	function js($file = null, $script = null)
	{
		$doc	= & JFactory::getDocument();
		
		// Add file
		if ($file) {
			$file	= strpos($file, '/') !== false ? $file : 'js:'. $file;
			$doc->addScript(uBarMooTools::_f($file));
		}
		
		// JS statement
		if ($script) {
			$doc->addScriptDeclaration(
				'window.addEvent("domready", function() {'.
					$script .
				'});'
			);
		}
	}
	
	function css($file = null, $css = null)
	{
		$doc	= & JFactory::getDocument();
		
		if ($file) {
			$file	= strpos($file, '/') !== false ? $file : 'css:'. $file;
			$doc->addStyleSheet(uBarMooTools::_f($file));
		}
		
		if ($css) {
			$doc->addStyleDeclaration($css);
		}
	}
	
	/*
	 * Returns file path
	 */
	function _f($file)
	{
		// Internal script
		if (strpos($file, ':') !== false) {
			list($type, $name)	= explode(':', $file);
			return JURI::root() .'administrator/components/com_ubar/assets/'. $type .'/'. $name .'.'. $type .'?'. UBAR_VERSION;
		}
		
		return $file .'?'. UBAR_VERSION;
	}
}

