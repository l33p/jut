<?php
defined('_JEXEC') or die;

/*
 * Toolbar button renderer
 */
class uBarBtnRenderer extends JObject
{
	function __construct($btn = null)
	{
		// Set button details
		if ($btn) {
			$this->setProperties($btn);
		}
		
		// Parameters
		if (!isset($this->params)) {
			$this->params	= new JParameter('');
		} elseif (is_string($this->params)) {
			$this->params	= new JParameter($this->params);
		}
		
		// References
		$this->_uri	= & JFactory::getURI();
		$this->_doc	= & JFactory::getDocument();
		$this->_user	= & JFactory::getUser();
	}
	
	function getButtonRenderer($type, $btn = null)
	{
		// Set button type
		if (is_object($type))
		{
			$btn	= $type;
			$type	= $btn->params->get('type');
		}
		
		// Include helper file
		$file	= UBAR_CLASSES.DS.'buttons'.DS.$type.'.php';
		if (!file_exists($file)) {
			return $this->setError('Invalid Button Type "'. $type .'"');
		}
		
		// Get helper class
		require_once($file);
		$class	= 'uBarBtn'. ucfirst($type);
		if (!class_exists($class)) {
			return $this->setError('Invalid Button Type "'. $type .'"');
		}
		
		// Load button details
		$b	= new $class($btn);
		
		return $b;
	}
	
	/*
	 * Renders a toolbar button
	 */
	function render()
	{
		// Implemented in child class
	}
	
	/*
	 * Returns basic button details for javascript
	 */
	function getButton()
	{
		$btn	= array();
		$btn['id']	= 'uBarBtn'. $this->getId();
		$btn['message']	= addslashes($this->get('desc', ''));
		$btn['icon']	= $this->formatIconUri($this->get('icon'));
		$btn['className']	= $this->params->get('_class', '');
		if (!strlen($btn['icon']) || $this->params->get('_dname', 0)) {
			$btn['text']	= addslashes($this->get('name'));
		}
		
		// Button position
		if ($this->params->get('_pos') == 1) {
			$btn['position']	= 1;
		}
		
		return $btn;
	}
	
	/*
	 * Check button
	 */
	function isIncluded()
	{
		if (!isset($this->_inc))
		{
			// Menu item
			$Itemid	= JRequest::getInt('Itemid', 0);
			$menus	= $this->params->get('_menus', 1);
			if ($menus < 2) {
				$this->_inc	= $menus == 1;
				return $this->_inc;
			}
			
			// Check Itemid
			$items	= (array) $this->params->get('_items', array());
			if (!in_array($Itemid, $items)) {
				$this->_inc	= false;
				return $this->_inc;
			}
			
			$this->_inc	= true;
		}
		
		return $this->_inc;
	}
	
	/*
	 * Returns button ID
	 */
	function getId()
	{
		if (!isset($this->_id)) {
			$this->_id	= $this->get('id', mt_rand());
		}
		
		return $this->_id;
	}
	
	/*
	 * Returns icon URI
	 */
	function getIcon($name) {
		return $this->_uri->toString(array('scheme','host','port')) . UBAR_ASSETS .'img/icons/'. $name;
	}
	function formatIconUri($icon)
	{
		if (strpos($icon, 'http') === 0) {
			return $icon;
		} elseif (strpos($icon, '/') !== false) {
			$icon	= JURI::root() . $icon;
		} elseif (strlen($icon)) {
			$icon	= $this->getIcon($icon);
		}
		
		return $icon;
	}
	
	/*
	 * Returns formatted URL
	 */
	function getUrl($url)
	{
		// Current URI
		if (strpos($url, '[url-64]') || strpos($url, '[return-64]')) {
			$rep	= base64_encode($this->_uri->toString());
			$url	= str_replace(array('[url-64]', '[return-64]'), $rep, $url);
		}
		if (strpos($url, '[url-en]') || strpos($url, '[return-en]')) {
			$rep	= urlencode($this->_uri->toString());
			$url	= str_replace(array('[url-en]', '[return-en]'), $rep, $url);
		}
		
		// User ID
		if (strpos($url, '[user-id]')) {
			$url	= str_replace('[user-id]', $this->_user->get('id', 0), $url);
		}
		if (strpos($url, '[username]')) {
			$url	= str_replace('[username]', $this->_user->get('username', ''), $url);
		}
		
		// Document title
		if (strpos($url, '[title]')) {
			$url	= str_replace('[title]', $this->_doc->getTitle(), $url);
		}
		
		// Security token
		if (strpos($url, '[token]')) {
			$url	= str_replace('[token]', JUtility::getToken(), $url);
		}
		
		// Get full URL
		if (strpos($url, 'http') !== 0 && strpos($url, '#') !== 0)
		{
			$b	= JURI::base(true);
			$url	= JRoute::_($url);
			$url	= strlen($b) ? str_replace($b .'/', JURI::root(), $url) : JURI::root() . substr($url, 1);
		}
		
		return $url;
	}
	
	/*
	 * Returns URL javascript object
	 */
	function getUrlOptions($target = '_self', $att = array(), $toArray = false)
	{
		switch ($target)
		{
			case '_blank':
				$opts	= array();
				break;
			
			case 'modal':
				$opts	= array('modal' => array(
					'handler'	=> 'iframe',
					'size' => array(
						'x' => @$att['width'],
						'y'	=> @$att['height']
					)
				));
				break;
			
			case 'popup':
				$opts	= $att;
				$opts['name']	= 'uBarPopup'. $this->getId() . mt_rand();
				break;
			
			default:
				$opts	= 'false';
		}
		
		return $toArray ? $opts : $this->getJsObj($opts);
	}
	
	/*
	 * Returns menu links formatted for uBar
	 */
	function getMenuLinks()
	{
		// Menu links
		$links	= array();
		$table	= $this->getTable();
		$mLinks	= $table->getMenuLinks();
		
		// Format links
		if (count($mLinks))
		{
			foreach ($mLinks as $l)
			{
				// URL options
				$pop	= array();
				if (strlen($l->get('width'))) {
					$pop['width']	= $l->get('width');
					$pop['height']	= $l->get('height');
				}
				
				$url	= $this->getUrl($l->get('url'));
				$pop	= $this->getUrlOptions($l->get('target'), $pop, true);
				
				$links[]	= array(
					'popup'	=> $pop,
					'url'	=> urlencode($url),
					'label'	=> array(
						'text'	=> $l->get('text', '??')
					)
				);
			}
		}
		
		// Formatted links
		return $links;
	}
	
	/*
	 * Shortcut to insert a button
	 */
	function getScript($func, $args) {
		return 'UTb.'. $func .'('. implode(',', $args) .');';
	}
	
	/*
	 * Shortcut to insert a javascript file
	 */
	function addScript($name) {
		$this->_doc->addScript(UBAR_ASSETS.'js/'. $name .'.js?'. UBAR_VERSION_INC);
	}
	
	/*
	 * Shortcut to get color from parameter
	 */
	function getClr($name, $default) {
		$color	= $this->params->get($name, $default);
		return strlen($color) > 2 ? $color : $default;
	}
	
	/*
	 * Get table object (for object methods)
	 */
	function getTable($details = null)
	{
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ubar'.DS.'tables'.DS.'btn.php');
		
		// Return table instance
		$inst	= & JTable::getInstance('Btn', 'Table');
		
		// Bind button details
		$details	= $details ? $details : $this;
		$inst->bind($details);
		
		return $inst;
	}
	
	/*
	 * Internal function to convert an array to JavaScript object
	 * See: libraries/joomla/html/html/behavior.php (_getJSObject)
	 */
	function getJsObj($a = array(), $toArray = false)
	{
		// Performance check
		if (empty($a)) {
			return $toArray ? '[]' : '{}';
		} elseif ($a == 'false' || $a == 'true' || is_numeric($a)) {
			return $a;
		}
		
		$e	= array();
		settype($a, 'array');
		
		// Loop through array elements
		foreach ($a as $k => $v)
		{
			// Key
			$k	= $toArray ? '' : $k .':';
			
			// Arrays
			if (is_array($v)) {
				$e[]	= $k . $this->getJsObj($v);
			}
			
			// Numbers, booleans
			elseif (is_numeric($v) || is_bool($v)) {
				$e[]	= $k . $v;
			}
			
			// Strings
			elseif (is_string($v)) {
				$e[]	= $k .'"'. $v .'"';
			}
		}
		
		$e	= implode(',', $e);
		return $toArray ? '['. $e .']' : '{'. $e .'}';
	}
	
	/*
	 * Miscellanous
	 */
	function setError($error) {
		parent::setError($error);
		return false;
	}
}

