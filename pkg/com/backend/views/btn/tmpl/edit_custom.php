<?php
defined('_JEXEC') or die;

// References
$b	= & $this->btn;
?>
<table width="100%" class="paramlist admintable" cellspacing="1">

	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[url]">
					<?php echo JText::_('SCRIPT LOC'); ?>
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
				<label for="misc">JavaScript</label>
			</span>
		</td>
		<td class="paramlist_value">
			<textarea name="misc" cols="43" rows="12" class="text_area" id="misc"><?php echo $b->misc; ?></textarea>
		</td>
	</tr>
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip">
				<label for="params[clk]">onClick</label>
			</span>
		</td>
		<td class="paramlist_value">
			<input class="inputbox" type="text" name="params[clk]" id="params[clk]" size="75" value="<?php echo $b->getParam('clk'); ?>" />
		</td>
	</tr>

</table>
