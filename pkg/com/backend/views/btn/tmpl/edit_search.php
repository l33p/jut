<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;

// Form action
$action	= $b->getParam('action', '');
$action	= strlen($action) ? $action : 'index.php?option=com_search&task=search';

// Form method
$method	= array();
$method[]	= JHTML::_('select.option', 'get', 'GET');
$method[]	= JHTML::_('select.option', 'post', 'POST');
$method	= JHTML::_('select.genericlist', $method, 'params[method]', 'class="inputbox"', 'value', 'text', $b->getParam('method', 'get'));

// Form target
$target	= array();
$target[]	= JHTML::_('select.option', '_self', JText::_('SELF'));
$target[]	= JHTML::_('select.option', '_blank', JText::_('BLANK'));
$target	= JHTML::_('select.genericlist', $target, 'params[target]', 'class="inputbox"', 'value', 'text', $b->getParam('target', '_self'));

// Input name
$input	= $b->getParam('input', '');
$input	= strlen($input) ? $input : 'searchword';
?>
<table width="100%" class="paramlist admintable" cellspacing="1">

	<!-- Form action URL -->
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[action]">
					<?php echo JText::_('FORM ACTION'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="params[action]" id="params[action]" size="75" value="<?php echo $action; ?>" />
		</td>
	</tr>

	<!-- Form method -->
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[method]">
					<?php echo JText::_('METHOD'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value"><?php echo $method; ?></td>
	</tr>

	<!-- Form target -->
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[target]">
					<?php echo JText::_('TARGET'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value"><?php echo $target; ?></td>
	</tr>

	<!-- Input name -->
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[input]">
					<?php echo JText::_('FORM INPUT'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="params[input]" id="params[input]" size="75" value="<?php echo $input; ?>" />
		</td>
	</tr>

</table>
