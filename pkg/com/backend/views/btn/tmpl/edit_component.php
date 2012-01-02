<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;
$p	= & $this->pane;

// URL params
$urlVars	= $b->getComponentUrl();
foreach ($urlVars as $k => $v) {
?>
<input type="hidden" name="urlparams[<?php echo $k; ?>]" value="<?php echo $v; ?>" />
<?php
}

// No parameters
if(!$this->urlParams->getNumParams())
{
?>
<fieldset class="adminform">
	<legend><?php echo JText::_('PARAMETERS'); ?></legend>
	<table width="100%" class="paramlist admintable" cellspacing="1">
		<tr>
			<td colspan="2"><i>
				<?php echo JText::_('There are no Parameters for this item'); ?>
			</i></td>
		</tr>
	</table>
</fieldset>
<?php
}

// URL parameters
else
{
	echo
	'<fieldset style="margin:7px 0 0 0;padding:0;border:none;">'.
	$p->startPane('menu-pane') .
	$p->startPanel(JText::_('Parameters - Basic'), 'param-page') .
	$this->urlParams->render('urlparams') .
	$p->endPanel() . $p->endPane() .'</fieldset>';
}

