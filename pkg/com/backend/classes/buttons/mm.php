<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: Menu (from menu manager) 
 */
class uBarBtnMm extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// Menutype ID
		$id	= (int) $this->params->get('menu');
		if ($id < 1) {
			return '';
		}
		
		// Get menu links
		$db	= & JFactory::getDBO();
		$db->setQuery(
			'SELECT m.id, m.name, m.link, m.type, m.browserNav'.
			' FROM #__menu AS m'.
			' LEFT JOIN #__menu_types AS t'.
			' ON m.menutype = t.menutype'.
			' WHERE m.published = 1'.
			' AND m.parent = 0'.
			' AND t.id = '. $id .
			' ORDER BY m.ordering'
		);
		$mLinks	= (array) $db->loadObjectList();
		if (empty($mLinks)) {
			return '';
		}
		
		// Format menu links
		$links	= array();
		foreach ($mLinks as $l)
		{
			// URL
			$url	= $l->link;
			if ($l->type != 'url') {
				$url	.= '&Itemid='. $l->id;
			}
			$url	= $this->getUrl($url);
			
			// Popup options
			switch ($l->browserNav)
			{
				// Popup
				case '2':
					$pop	= 'popup';
					break;
				
				// Blank window
				case '1':
					$pop	= '_blank';
					break;
				
				default:
					$pop	= '_self';
			}
			$pop	= $this->getUrlOptions($pop, array(), true);
			
			$links[]	= array(
				'url'	=> urlencode($url),
				'label'	=> array('text' => $l->name),
				'popup'	=> $pop
			);
		}
		
		// Script
		return $this->getScript('addMenu', array(
			$this->getJsObj($this->getButton()),
			$this->getJsObj($links, true)
		));
	}
}

