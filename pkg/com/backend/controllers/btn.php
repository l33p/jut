<?php
defined('_JEXEC') or die;

class UbarControllerBtn extends UbarController
{
	function UbarControllerBtn()
	{
		parent::__construct();
		
		// Register Extra tasks
		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
		$this->registerTask('publish', 'state');
		$this->registerTask('unpublish', 'state');
		$this->registerTask('orderup', 'order');
		$this->registerTask('orderdown', 'order');
		$this->registerTask('accesspublic', 'access');
		$this->registerTask('accessregistered', 'access');
		$this->registerTask('accessspecial', 'access');
	}
	
	function edit()
	{
		JRequest::setVar('view', 'btn');
		JRequest::setVar('hidemainmenu', 1);
		
		parent::display();
	}
	
	function save()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Save button
		$model	= & $this->getModel('Btn');
		$saved	= $model->save();
		$msg	= $saved ? JText::_('ITEM SAVED') : $model->getError();
		
		// Return to edit button or return to button list.
		// NOTE: Return on error too.
		$btn	= & $model->getButton();
		$error	= (!$saved && $btn->id > 0);
		if ($this->getTask() == 'apply' || $error) {
			$rdir	= $btn->getEditUrl();
		} else {
			$rdir	= index .'&view=btns';
		}
		
		$this->setRedirect($rdir, $msg);
	}
	
	function remove()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Buttons
		$IDs	= JRequest::getVar('bid', array(), 'post', 'array');
		
		// Save state
		$model	= $this->getModel('Btn');
		if ($total = $model->remove($IDs)) {
			$msg	= JText::sprintf('ITEMS REMOVED', $total);
		} else {
			$msg	= $model->getError();
		}
		
		// Redirect
		$this->setRedirect(index .'&view=btns', $msg);
	}
	
	function copy()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Get original button
		$model	= & $this->getModel('Btn');
		$IDs	= JRequest::getVar('bid', array(0), 'POST', 'array');
		$model->setId((int) $IDs[0]);
		
		// Create a copy
		$btn	= & $model->getButton();
		$copy	= $btn;
		$copy->id	= 0;
		$copy->name	= '';
		$copy->isCopy	= true;
		$model->set('btn.id', 0);
		$model->set('btn.obj', $copy);
		$this->setMessage(JText::_('COPY') .': "'. $btn->name .'"');
		
		// Display edit form
		$view	= & $this->getView('Btn', 'Html');
		$view->setModel($model, true);
		$view->display();
	}
	
	function state()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Buttons
		$IDs	= JRequest::getVar('bid', array(), 'post', 'array');
		
		// Save state
		$model	= $this->getModel('Btn');
		$enable	= $this->getTask() == 'publish' ? 1 : 0;
		if ($total = $model->publish($IDs, $enable)) {
			$msg	= $enable == 1 ? 'ITEMS PUBLISHED' : 'ITEMS UNPUBLISHED';
			$msg	= JText::sprintf($msg, $total);
		} else {
			$msg	= $model->getError();
		}
		
		// Redirect
		$this->setRedirect(index .'&view=btns', $msg);
	}
	
	function saveorder()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Get new order
		$IDs	= JRequest::getVar('bid', array(), 'post', 'array');
		$orders	= JRequest::getVar('order', array(), 'post', 'array');
		JArrayHelper::toInteger($IDs);
		JArrayHelper::toInteger($orders);
		
		// Save order
		$model	= $this->getModel('Btn');
		$msg	= $model->reorder($IDs, $orders) ? JText::_('NEW ORDERING SAVED') : $model->getError();
		
		// Redirect
		$this->setRedirect(index .'&view=btns', $msg);
	}
	
	function order()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Get button IDs
		$IDs	= JRequest::getVar('bid', array(), 'post', 'array');
		JArrayHelper::toInteger($IDs);
		
		// Order buttons
		$model	= & $this->getModel('Btn');
		$order	= $this->getTask() == 'orderup' ? -1 : 1;
		$msg	= $model->orderItem($IDs[0], $order) ? JText::_('ITEM SAVED') : $model->getError();
		
		// Redirect
		$this->setRedirect(index .'&view=btns', $msg);
	}
	
	function access()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Get button IDs
		$IDs	= JRequest::getVar('bid', array(), 'post', 'array');
		JArrayHelper::toInteger($IDs);
		
		// Get access level
		switch ($this->getTask())
		{
			case 'accessspecial':
				$access	= 2;
				break;
			
			case 'accessregistered':
				$access	= 1;
				break;
			
			default:
				$access	= 0;
		}
		
		// Set access
		$model	= & $this->getModel('Btn');
		$msg	= $model->access($IDs[0], $access) ? JText::_('ITEM SAVED') : $model->getError();
		
		// Redirect
		$this->setRedirect(index .'&view=btns', $msg);
	}
	
	// Install default buttons
	function install()
	{
		JRequest::checkToken() or jexit('invalid token');
		
		// Install buttons
		$model	= & $this->getModel('Install');
		$msg	= $model->install() ? JText::_('DONE') : $model->getError();
		
		// Redirect
		$this->setRedirect(index .'&view=btns', $msg);
	}
	
	function cancel() {
		parent::cancel(index .'&view=btns');
	}
}

