<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;
$pane	= & $this->pane;
$p	= & $this->modParams;

// Open pane
echo $pane->startPane('menu-pane');

// Basic parameters
echo $pane->startPanel(JText::_('PARAMETERS'), 'b-params');
echo $p->getNumParams() ? $p->render('params') : $this->loadTemplate('module_np');
echo $pane->endPanel();

// Advanced parameters
if ($p->getNumParams('advanced')) {
	echo $pane->startPanel(JText::_('ADVANCED PARAMETERS'), 'a-params') .
		$p->render('params', 'advanced') . $pane->endPanel();
}

// Legacy parameters
if ($p->getNumParams('legacy')) {
	echo $pane->startPanel(JText::_('LEGACY PARAMETERS'), 'l-params') .
		$p->render('params', 'legacy') . $pane->endPanel();
}

// Other parameters
if ($p->getNumParams('other')) {
	echo $pane->startPanel(JText::_('Other Parameters'), 'o-params') .
		$p->render('params', 'other') . $pane->endPanel();
}

echo $pane->endPane();

// Module name
echo '<input type="hidden" name="params[_mod]" value="'. $p->get('_mod') .'" />';


