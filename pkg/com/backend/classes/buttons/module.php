<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

// JModule files
jimport('joomla.document.renderer');
require_once(JPATH_LIBRARIES.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php');
require_once(JPATH_LIBRARIES.DS.'joomla'.DS.'document'.DS.'html'.DS.'renderer'.DS.'module.php');

/*
 * Button Type: Module
 */
class uBarBtnModule extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// Render module
		$html	= '';
		$mod	= new JObject();
		$mod->set('id', 0);
		$mod->set('user', 0);
		$mod->set('style', null);
		$mod->set('control', '');
		$mod->set('showtitle', 0);
		$mod->set('content', null);
		$mod->set('position', 'uBar');
		$mod->set('module', $this->params->get('_mod'));
		$mod->set('name', substr($mod->module, 4));
		$mod->set('title', $mod->name);
		$mod->set('params', $this->params->toString());
		$html	= JModuleHelper::renderModule($mod, $this->params->toArray());
		
		// Put style sheets in head
		$head	= '';
		$this->st($html, $head, '<link', '#<link[^>]+rel=[\'"]stylesheet[\'"][^>]+/>#i');
		
		// Style declarations
		$this->st($html, $head, '<style', '#<style\b[^>]*?>(\n|\s|.)*?</style>#i');
		
		// Javascript
		$this->st($html, $head, '<script', '#<script\b[^>]*?>(\n|\s|.)*?</script>#i');
		
		//if ($mod->module == 'mod_uddeim') die($head);
		//if ($mod->module == 'mod_uddeim') die("<!--\n\n". $html);
		
		if (JString::strlen($head)) {
			$head	= "<!-- uBar: module head tags -->\n". $head;
			$this->_doc->addCustomTag($head);
		}
		
		// Script
		return $this->getScript('addHtml', array(
			$this->getJsObj($this->getButton()),
			'"'. urlencode($html) .'"'
		));
	}
	
	// Strips header code
	function st(&$html, &$head, $test, $regex)
	{
		if (stripos($html, $test) === false) {
			return;
		}
		
		$matches	= array();
		if (preg_match_all($regex, $html, $matches))
		{
			// Remove matches
			$html	= preg_replace($regex, '', $html);
			
			foreach ($matches[0] as $match)
			{
				if (!strlen(trim($match))) {
					continue;
				}
				
				$head	.= $match;
				//$html	= JString::str_ireplace($match, '', $html);
			}
		}
	}
}

