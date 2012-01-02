<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;
?>
<table width="100%" class="paramlist admintable" cellspacing="1">

	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[w]">
					<?php echo JText::_('WIDTH'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="params[w]" id="params[w]" size="20" maxlength="3" onblur="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo $b->getParam('w', 345); ?>" />
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
			<input class="inputbox" type="text" name="params[h]" id="params[h]" size="20" maxlength="3" onblur="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo $b->getParam('h', 135); ?>" />
		</td>
	</tr>

</table>
