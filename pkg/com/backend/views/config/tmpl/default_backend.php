<?php
defined('_JEXEC') or die;

/*
 * Show feed
 */
$o	= array();
$o[]	= JHTML::_('select.option', 0, JText::_('NO'));
$o[]	= JHTML::_('select.option', 1, JText::_('YES'));
$t	= JText::_('NEWSFEED') .'::'. JText::_('NEWSFEED DESC');
?>
<table width="100%" class="paramlist admintable" cellspacing="1">
<tr>
	<td width="40%" class="paramlist_key">
		<span class="editlinktip">
			<label for="params[feed]" class="hasTip" title="<?php echo $t; ?>">
				<?php echo JText::_('NEWSFEED'); ?>
			</label>
		</span>
	</td>
	<td class="paramlist_value">
		<?php echo JHTML::_('select.genericlist', $o, 'params[feed]', 'class="inputbox"', 'value', 'text', $this->plgParams->get('feed', 1)); ?>
	</td>
</tr>
</table>