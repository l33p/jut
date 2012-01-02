<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;

// Menu list
$menu	= $this->getMenuSelect('params[menu]', $b->getParam('menu'));
?>
<table width="100%" class="paramlist admintable" cellspacing="1">

	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[menu]">
					<?php echo JText::_('MENU SELECTION'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<?php echo $menu; ?>
		</td>
	</tr>

</table>
