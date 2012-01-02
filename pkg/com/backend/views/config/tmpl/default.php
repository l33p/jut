<?php
defined('_JEXEC') or die;

// Plugin parameters
$plg	= & $this->plgParams;

?>
<form name="adminForm" action="" method="post">

<!-- Preview -->
<div class="cpanel-left">
	<div id="preview-w">
<?php
if (count($this->btns)) {
	echo $this->loadTemplate('preview');
}
?>
	</div>
</div>

<!-- Parameters -->
<div class="cpanel-right">
	<fieldset class="adminform">
	<legend><?php echo JText::_('PARAMETERS'); ?></legend>
<?php
// Open pane
echo JHtml::_('sliders.start', 'paramspane', array('useCookie' => 1));

// Appearance panel
echo JHtml::_('sliders.panel', JText::_('PREVIEW'), 'paramscss');
echo $plg->render();

// Settings panel
echo JHtml::_('sliders.panel', JText::_('SETTINGS'), 'paramssettings');
echo $plg->render('params', 'advanced');

// Backend settings panel
echo JHtml::_('sliders.panel', JText::_('PUBLIC BACKEND'), 'paramsbackend');
echo $this->loadTemplate('backend');

// Close pane
//echo $this->pane->endPane();
echo JHtml::_('sliders.end');
?>
	</fieldset>
</div>
<div class="clr"></div>

<!-- CSS -->
<?php
$css	= $plg->get('css', '');
$css	= strlen($css) ? base64_decode($css) : '';

// Display CSS textarea in pane
echo
'<div class="col100">'.
	JHtml::_('sliders.start', 'csspane', array('useCookie' => 1)) .
	JHtml::_('sliders.panel', 'CSS', 'csstextarea') .
	'<textarea name="params[css]" style="width:100%;height:200px;">'.
		$css .'</textarea>'.
	JHtml::_('sliders.end') .
'</div>';
?>

	<input type="hidden" name="option" value="com_jut" />
	<input type="hidden" name="controller" value="config" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
