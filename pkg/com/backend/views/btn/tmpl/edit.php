<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;

// Type
$type	= $b->getParam('type');

// Icon browse
$icon	= JHTML::link(
	index .'&view=mm', JText::_('ICON'), array(
		'class'	=> 'modal',
		'rel'	=> '{handler:\'iframe\',size:{x:700,y:330}}'
	)
);

// Position
$pos	= array();
$pos[]	= JHTML::_('select.option', 0, JText::_('LEFT'));
$pos[]	= JHTML::_('select.option', 1, JText::_('RIGHT'));
$pos	= JHTML::_('select.genericlist', $pos, 'params[_pos]', 'class="inputbox"', 'value', 'text', $b->getParam('_pos', '0'), 'paramspos');

// Menus
$menus	= (int) $b->getParam('_menus', 1);
$m	= array(
	($menus == 0 ? ' checked="checked"' : ''),
	($menus == 1 ? ' checked="checked"' : ''),
	($menus == 2 ? ' checked="checked"' : '')
);

// Menu list
$list	= JHTML::_('menu.linkoptions');
$list	= JHTML::_('select.genericlist', $list, 'params[_items][]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $b->getParam('_items', ''), 'paramsitems');

?>
<script type="text/javascript">

// Submit Button override
function submitbutton(task)
{
	// Cancel
	if (task == 'cancel') return submitform(task);
	
	// Validate form
	frm	= document.adminForm;
	if (frm.name.value.trim().length < 1) {
		alert('<?php echo addslashes(JText::sprintf('MISSING PARAM', JText::_('NAME'))); ?>');
		return frm.name.focus();
	}
	
	submitform(task);
}

</script>

<form action="<?php echo index; ?>" method="post" name="adminForm">

<div class="col width-45">

<fieldset class="adminform">
	<legend><?php echo JText::_('BUTTON'); ?></legend>
	<table class="admintable">
	<tr>
		<td width="100" align="right" class="key">
			<label for="type">
				<?php echo JText::_('TYPE'); ?>
			</label>
		</td>
		<td style="font-style:italic;">
			<?php echo JText::_('BT '. $type); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name" class="hasTip" title="<?php echo JText::_('DNAME'); ?>">
				<?php echo JText::_('NAME'); ?>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="name" id="name" size="30" maxlength="20" value="<?php echo $b->name;?>" />
			<input class="inputbox" type="checkbox" name="params[_dname]" <?php echo $b->getParam('_dname', 0) ? 'checked="checked"' : '';?> />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="desc">
				<?php echo JText::_('DESCRIPTION'); ?>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="desc" id="desc" size="70" maxlength="75" value="<?php echo $b->desc;?>" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="icon">
				<?php echo $icon; ?>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="icon" id="icon" size="70" maxlength="200" value="<?php echo $b->icon;?>" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="paramspos">
				<?php echo JText::_('POSITION'); ?>
			</label>
		</td>
		<td><?php echo $pos; ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="paramsclass">CSS Class</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="params[_class]" id="paramsclass" size="30" value="<?php echo $b->getParam('_class', '');?>" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="ordering">
				<?php echo JText::_('ORDERING'); ?>
			</label>
		</td>
		<td>
			<?php echo $this->getOrdering($b); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="access">
				<?php echo JText::_('ACCESS'); ?>
			</label>
		</td>
		<td>
			<?php echo JHTML::_('list.accesslevel', $b); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="type">
				<?php echo JText::_('MENUS'); ?>
			</label>
		</td>
		<td>
			<input type="radio" name="params[_menus]" id="menus1" value="1"<?php echo $m[1]; ?> /> 
			<label for="menus1"><?php echo JText::_('ALL'); ?></label> 
			<input type="radio" name="params[_menus]" id="menus0" value="0"<?php echo $m[0]; ?> /> 
			<label for="menus0"><?php echo JText::_('NONE'); ?></label> 
			<input type="radio" name="params[_menus]" id="menus2" value="2"<?php echo $m[2]; ?> /> 
			<label for="menus2"><?php echo JText::_('SELECT FROM LIST'); ?> ... &#8595;</label> 
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="name"><?php echo JText::_('SELECT ITEM'); ?></label>
		</td>
		<td><?php echo $list; ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="published">
				<?php echo JText::_('ENABLED'); ?>
			</label>
		</td>
		<td>
			<input class="inputbox" type="checkbox" name="published" id="published" <?php echo $b->published ? 'checked="checked"' : '';?> />
		</td>
	</tr>
	</table>
</fieldset>

</div>

<div class="col width-55">

<?php
// Parameters
if ($type != 'component')
{
?>
	<fieldset class="adminform">
		<legend id="params-title">
			<?php echo JText::_('PARAMETERS'); ?>
		</legend>
		<?php echo $this->loadTemplate($type); ?>
	</fieldset>
<?php
}
else {
	echo $this->loadTemplate($type);
}
?>
</div>

<div class="clr"></div>

<?php echo $this->loadTemplate($type .'_misc'); ?>

	<input type="hidden" name="option" value="com_ubar" />
	<input type="hidden" name="controller" value="btn" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $b->id; ?>" />
	<input type="hidden" name="params[type]" value="<?php echo $type; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
