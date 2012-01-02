<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;

// Login URL
$login	= $b->getParam('login');
$login	= strlen($login) ? $login : 'index.php?option=com_user&view=login&return=[return-64]';

// Menu helper
$this->loadHelper('mootools');
uBarMooTools::editMenu('menu-items', $b->getMenuLinks());

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
$target	= JHTML::_('select.genericlist', $target, 'item-target', 'class="inputbox"', 'value', 'text');
?>
<script type="text/javascript">

// Linkr
function LinkrInsert(y, o, e) {
	$('item-url').value	= (y == 'object' ? o.url : o);
}

// Save menu list
document.adminForm.onsubmit	= function()
{
	var frm		= $(document.adminForm);
	var items	= uBarMenu.getMenuItems();
	items.each(function(mi, i)
	{
		new Element('input', {
			type : 'hidden',
			name : 'items['+ i +']',
			value :
				't='+ mi.text +'&'+
				'u='+ mi.url +'&'+
				'x='+ mi.target +'&'+
				'w='+ mi.width +'&'+
				'h='+ mi.height
		}).injectInside(frm);
	});
};

</script>
<table width="100%" class="paramlist admintable" cellspacing="1">

	<!-- Login URL -->
	<tr>
		<td class="paramlist_description" colspan="2" style="text-align:center">
			<?php echo JText::_('BT USER INST'); ?>
		</td>
	</tr>
	<tr>
		<td width="40%" class="paramlist_key hasTip" title="::<?php echo JText::_('BT USER INST'); ?>">
			<span class="editlinktip">
				<label for="params[login]">
					<?php echo JText::_('LOGIN'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="params[login]" id="params[login]" size="75" value="<?php echo $login; ?>" />
		</td>
	</tr>
	
	<!-- Instructions -->
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td class="paramlist_description" colspan="2" style="text-align:center">
			<?php echo JText::_('MENU INST'); ?>
		</td>
	</tr>

	<!-- Link text -->
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="item-text">
					<?php echo JText::_('TEXT'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input type="text" id="item-text" size="75" class="inputbox" />
		</td>
	</tr>
	
	<!-- Link URL -->
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="item-url">
					<?php echo $urlTxt; ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input type="text" id="item-url" size="75" class="inputbox" />
		</td>
	</tr>
	
	<!-- Link target -->
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="item-target">
					<?php echo JText::_('TARGET'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<?php echo $target; ?>
		</td>
	</tr>
	
	<!-- Popup size -->
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="item-w">
					<?php echo JText::_('WIDTH'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="item-w" id="item-w" size="20" maxlength="5" onblur="this.value=this.value.replace(/[^0-9]/g,'');" />
		</td>
	</tr>
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="item-h">
					<?php echo JText::_('HEIGHT'); ?>
				</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="item-h" id="item-h" size="20" maxlength="5" onblur="this.value=this.value.replace(/[^0-9]/g,'');" />
		</td>
	</tr>
	
	<!-- Item reference -->
	<input type="hidden" id="item-ref" value="-1" />
	
	<!-- Save, cancel buttons -->
	<tr>
		<td class="paramlist_description" colspan="2" style="text-align:center">
			<input type="button" id="item-save" class="inputbox" value="<?php echo JText::_('SAVE'); ?>" />
			<input type="button" id="item-cancel" class="inputbox" value="<?php echo JText::_('CANCEL'); ?>" />
		</td>
	</tr>

</table>
