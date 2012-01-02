<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');


class UbarViewMm extends UbarView
{
	function display($tpl = null)
	{
		// Assign references
		$this->assignRef('form', $this->get('FormData'));
		
		// Set vars
		JRequest::setVar('tmpl', 'component');
		
		// Load styles
		$this->loadScripts();
		
		parent::display($tpl);
	}
	
	function loadScripts()
	{
		$d	= & JFactory::getDocument();
		
		// CSS styles
		$d->addStyleDeclaration(
			'#fileList{'.
				'width:670px;'.
				'height:220px;'.
				'overflow:auto;'.
				'clear:both;'.
			'}'.
			'#fileList div.item{'.
				'float:left;'.
				'border:1px solid #fff;'.
				'background-color:#fff;'.
				'text-align:center;'.
			'}'.
			'#fileList div.item div.border{'.
				'margin:5px;'.
				'vertical-align:middle;'.
				'overflow:hidden;'.
			'}'.
			'#fileList div.item div.border a{'.
				'display:block;'.
			'}'.
			'#fileList div.item:hover{'.
				'border:1px solid #0B55C4;'.
				'background-color:#d2d7e0;'.
				'cursor:pointer;'.
			'}'.
			'form fieldset{'.
				'margin:5px;'.
				'padding:5px;'.
				'border:none;'.
				'border-top:3px solid #d2d7e0;'.
				'border-bottom:1px dotted #d2d7e0;'.
			'}'.
			'form fieldset legend{'.
				'margin:5px;'.
				'padding:5px;'.
			'}'
		);
		
		// Javascript
		JHTML::_('behavior.tooltip');
		$d->addScriptDeclaration(
			'var selectIcon	= function(u, f)'.
			'{'.
				'if (document.uploadForm.deli.checked == true) {'.
					'if (confirm("'. JText::_('VALIDDELETEITEMS', true) .'")) {'.
						'window.location.href = "'. $this->form->deleteURL .'"+ f;'.
					'}'.
				'} else {'.
					'window.parent.document.adminForm.icon.value=u;'.
					'window.parent.document.getElementById("sbox-window").close();'.
				'}'.
			'}'
		);
	}
}

