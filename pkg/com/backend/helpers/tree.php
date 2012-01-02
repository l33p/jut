<?php
defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.base.tree');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/*
 * Tree helper
 * See "administrator/components/com_menus/classes/ilink.php"
 */
class uBarTree extends JTree
{
	var $_option	= null;
	var $_output	= '';
	
	function __construct($component)
	{
		parent::__construct();
		
		// Component
		$this->_option = preg_replace('#\W#', '', $component);
		
		// Tree
		if (!$this->buildTreeFromMetadata($this->_root)) {
			$this->buildTreeFromFolders($this->_root);
		}
	}
	
	/*
	 * Build tree from metadata file
	 */
	function buildTreeFromMetadata(&$parent, $url = array(), $file = null, $xpath = 'menu')
	{
		// XML file
		$file	= $file ? $file : JPATH_SITE.DS.'components'.DS.$this->_option.DS.'metadata.xml';
		if (is_object($file)) {
			$x	= $file;
		} elseif (!$x = $this->_getXML($file, $xpath)) {
			return false;
		}
		
		// No options
		if ($x->attributes('options') == 'none')
		{
			$n	= & new uBarTreeNode($x->attributes('title'), $x->attributes('msg'), $url);
			$n->url['option']	= $this->_option;
			$parent->addChild($n);
			return true;
		}
		
		// Get options
		$o	= $x->getElementByPath('options');
		if (!$o) {
			return false;
		}
		
		$children	= $o->children();
		foreach ($children as $c)
		{
			$n	= & new uBarTreeNode($c->attributes('name'), $c->attributes('msg'), $url);
			$n->url['option']	= $this->_option;
			
			// Query variable
			if ($c->name() == 'option') {
				$n->url[$o->attributes('var')]	= $c->attributes('value');
			}
			
			$parent->addChild($n);
		}
		
		return true;
	}
	
	/*
	 * Build tree from views folder
	 */
	function buildTreeFromFolders()
	{
		// "views" folder
		$folder	= JPATH_SITE.DS.'components'.DS.$this->_option.DS.'views';
		if (!JFolder::exists($folder)) {
			return false;
		}
		
		// Performance check
		$views	= JFolder::folders($folder);
		if (!is_array($views) || !count($views)) {
			return false;
		}
		
		// Get views
		foreach ($views as $v)
		{
			// Skip hidden views
			if (strpos($v, '_') !== false) {
				continue;
			}
			
			// Query parameters
			$url	= array('option' => $this->_option, 'view' => $v);
			
			// Metadata
			if ($ve = $this->_getXML($folder.DS.$v.DS.'metadata.xml', 'view'))
			{
				// Skip hidden views
				if ($ve->attributes('hidden') == 'true') {
					continue;
				}
				
				// Get node
				$n	= & new uBarTreeNode($ve->attributes('title'), null, $url);
				$this->addChild($n);
				
				// Add message
				if ($m = $ve->getElementByPath('message')) {
					$n->setMsg($m->data());
				}
				
				// Get layouts from metadata
				if (!$this->buildTreeFromMetadata($n, $url, $ve)) {
					$this->getLayouts($v, $folder.DS.$v, $n);
				}
			}
			
			// Manually add layouts
			else
			{
				$n	= & new uBarTreeNode(ucfirst($v), null, $url);
				$this->addChild($n);
				$this->getLayouts($v, $folder.DS.$v, $n);
			}
		}
		
		return true;
	}
	
	function getLayouts($view, $path, &$node)
	{
		// "layouts" folder
		$folder	= $path.DS.'tmpl';
		if (!JFolder::exists($folder)) {
			return false;
		}
		
		// Performance check
		$layouts	= JFolder::files($folder, '.php$');
		if (!is_array($layouts) || !count($layouts)) {
			return false;
		}
		
		// Get layouts
		foreach ($layouts as $l)
		{
			// Skip layout template files
			if (strpos($l, '_') !== false) {
				continue;
			}
			
			// Query parameters
			$l		= JFile::stripext($l);
			$url	= array('option' => $this->_option, 'view' => $view);
			if ($l != 'default') {
				$url['layout']	= $l;
			}
			
			// Metadata
			if ($le = $this->_getXML($folder.DS.$l.'.xml', 'layout'))
			{
				// Skip hidden layouts
				if ($le->attributes('hidden') == 'true') {
					continue;
				}
				
				// Get node
				$n	= & new uBarTreeNode($le->attributes('title'), null, $url);
				$node->addChild($n);
				
				// Add message
				if ($m = $le->getElementByPath('message')) {
					$n->setMsg($m->data());
				}
			}
			
			// Manually add layouts
			else
			{
				$n	= & new uBarTreeNode(ucfirst($l), null, $url);
				$node->addChild($n);
			}
		}
		
		return true;
	}
	
	/*
	 * Renders tree
	 */
	function render()
	{
		// Reset current node
		$this->reset();
		
		// HTML code
		$this->_output	= '';
		
		// Recurse through children
		while ($this->_current->hasChildren())
		{
			$this->_output	.= '<ul>';
			$children	= $this->_current->getChildren();
			
			// Add children to tree HTML
			$n	= count($children);
			for ($i = 0; $i < $n; $i++)
			{
				$isLast	= ($i == $n - 1);
				$this->_current = & $children[$i];
				$this->renderLevel($isLast);
			}
			
			$this->_output	.= '</ul>';
		}
		
		return $this->_output;
	}
	
	/*
	 * Renders tree node
	 */
	function renderLevel($isLast = false)
	{
		// LI class
		$li		= $isLast ? ' class="last"' : '';
		
		// DIV class, URL
		if ($this->_current->hasChildren())
		{
			$url	= '';
			$div	= 'node-open';
		}
		else
		{
			$div	= 'leaf';
			$url	= index .'&task=btn:edit&type=component';
			foreach ($this->_current->url as $key => $value) {
				$url	.= '&url['. $key .']='. $value;
			}
			$url	= ' href="'. $url .'"';
		}
		
		// Node HTML
		$this->_output	.=
		'<li'. $li .'>'.
		'<div class="'. $div .'">'.
			'<span></span>'.
			'<a class="hasTip"'. $url .' title="'.
				JText::_($this->_current->name) .'::'.
				JText::_($this->_current->msg) .'">'.
				JText::_($this->_current->name) .'</a>'.
		'</div>';
		
		// Recurse through children
		while ($this->_current->hasChildren())
		{
			$this->_output	.= '<ul>';
			$children	= $this->_current->getChildren();
			$n	= count($children);
			
			// Add children to HTML
			for ($i = 0; $i < $n; $i++)
			{
				$isLast	= ($i == $n - 1);
				$this->_current = & $children[$i];
				$this->renderLevel($isLast);
			}
			
			$this->_output	.= '</ul>';
		}
		
		// Finish HTML
		$this->_output	.= '</li>';
	}
	
	/*
	 * Returns an XML node
	 */
	function _getXML($file, $xpath)
	{
		// Check file
		if (!JFile::exists($file)) {
			return false;
		}
		
		// Load XML
		$parser	= & JFactory::getXMLParser('Simple');
		if (!$parser->loadFile($file)) {
			return false;
		}
		
		// Get element
		if (!isset($parser->document)) {
			return false;
		}
		
		return $parser->document->getElementByPath($xpath);
	}
}


/*
 * See "iLinkNode" class
 */
class uBarTreeNode extends JNode
{
	var $name	= null;
	var $msg	= null;
	var $url	= array();
	
	function __construct($name, $msg = null, $url = array())
	{
		$this->name	= JString::trim($name);
		$this->msg	= JString::trim($msg);
		$this->url	= array_merge($this->url, (array) $url);
	}
	
	function setMsg($msg) {
		$this->msg	= JString::trim($msg);
	}
}

