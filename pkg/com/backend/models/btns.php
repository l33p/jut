<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');


class UbarModelBtns extends UbarModel
{
	function UbarModelBtns()
	{
		parent::__construct();
		
		
	}
	
	function getButtons()
	{
		if (!$this->get('btn.list'))
		{
			$o	= $this->getOrder();
			$bl	= (array) $this->_getList(
				'SELECT b.*, g.name AS groupname '.
				'FROM #__ubar_btn AS b '.
				'LEFT JOIN #__groups AS g ON g.id = b.access '.
				'ORDER BY '. $o['order'] .
				' '. $o['order_Dir']
			);
			
			// Parameters
			for ($i = 0, $n = count($bl); $i < $n; $i++) {
				$bl[$i]->params	= new JParameter($bl[$i]->params);
			}
			
			$this->set('btn.list', $bl);
		}
		
		return $this->get('btn.list');
	}
	
	function getOrder()
	{
		if (!$this->get('btn.order'))
		{
			$o	= array();
			global $mainframe;
			$o['order']	= $mainframe->getUserStateFromRequest('ubar.order', 'filter_order', 'ordering', 'word');
			$o['order_Dir']	= $mainframe->getUserStateFromRequest('ubar.dir', 'filter_order_Dir', 'ASC', 'word');
			
			$this->set('btn.order', $o);
		}
		
		return $this->get('btn.order', array());
	}
	
	function getPagination()
	{
		if (!$this->get('page'))
		{
			$bs	= $this->getButtons();
			jimport('joomla.html.pagination');
			$p	= new JPagination(count($bs), 0, 0);
			
			$this->set('page', $p);
		}
		
		return $this->get('page');
	}
}

