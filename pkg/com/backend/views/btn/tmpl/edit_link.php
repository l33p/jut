<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;

// Linkr
$urlTxt	= 'URL';
if (strlen($this->getLinkrLink('')))
{
	$urlTxt	= JHTML::link(
		$this->getLinkrLink(), 'URL', array(
			'onclick'	=> 'return popLinkr(this)'
	));
}

// Target
$target	= array();
$target[]	= JHTML::_('select.option', '_self', JText::_('SELF'));
$target[]	= JHTML::_('select.option', '_blank', JText::_('BLANK'));
$target[]	= JHTML::_('select.option', 'popup', JText::_('POPUP'));
$target[]	= JHTML::_('select.option', 'modal', JText::_('LIGHTBOX'));
$target	= JHTML::_('select.genericlist', $target, 'params[target]', 'class="inputbox"', 'value', 'text', $b->getParam('target', 'self'));
?>
<script type="text/javascript">

// Linkr
function LinkrInsert(y, o, e) {
	document.adminForm['params[url]'].value	= (y == 'object' ? o.url : o);
}

</script>
<table width="100%" class="paramlist admintable" cellspacing="1">

	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[url]">
					<?php echo $urlTxt; ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="params[url]" id="params[url]" size="75" value="<?php echo $b->getParam('url'); ?>" />
		</td>
	</tr>
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[target]">
					<?php echo JText::_('TARGET'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<?php echo $target; ?>
		</td>
	</tr>
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[w]">
					<?php echo JText::_('WIDTH'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="params[w]" id="params[w]" size="20" maxlength="5" onblur="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo $b->getParam('w'); ?>" />
		</td>
	</tr>
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[h]">
					<?php echo JText::_('HEIGHT'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="params[h]" id="params[h]" size="20" maxlength="5" onblur="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo $b->getParam('h'); ?>" />
		</td>
	</tr>

</table>
