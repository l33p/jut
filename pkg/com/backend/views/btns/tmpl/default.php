<?php
defined('_JEXEC') or die;

/*
 * Default buttons alert
 */
?>
<script type="text/javascript">
function submitbutton(task)
{
	if (task != 'install') return submitform(task);
	
	// Confirm installation
	if (confirm('<?php echo JText::_('CONFIRM DEFAULT INSTALL', true); ?>')) {
		return submitform(task);
	}
}
</script>
<?php

/*
 * Feed rotator
 */
if ($this->plgParams->get('feed', 1))
{
?>
<div style="padding:5px 0;text-align:center;">
	<a target="_blank" href="http://feeds.feedburner.com/~r/ubar/~6/1">
		<img src="http://feeds.feedburner.com/ubar.1.gif" alt="uBar">
	</a>
</div>
<?php
}

/*
 * Button list
 */
?>
<form action="index.php?option=com_ubar" method="post" name="adminForm">
<?php
/*
 * Empty list
 */
$n	= count( $this->btns );
if ($n < 1)
{
?>
<div style="padding:20px;text-align:center;letter-spacing:2px;">
	<?php echo JText::_('NO RECORDS FOUND'); ?>
</div>
<?php
}

else
{
	// References
	$o	= & $this->order;

	// Enabled
	$enabled	= JHTML::image('administrator/images/tick.png', '+');
	$disabled	= JHTML::image('administrator/images/publish_x.png', '-');
?>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="40px" align="center">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $n; ?>);"/>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'BUTTONS', 'name', $o['order_Dir'], $o['order']); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ENABLED', 'published', $o['order_Dir'], $o['order']); ?>
			</th>
			<th width="75px">
				<?php echo JHTML::_('grid.sort', 'ORDER', 'ordering', $o['order_Dir'], $o['order']); ?>
			</th>
			<th width="1%">
				<?php echo JHTML::_('grid.order', $this->btns, 'filesave.png', 'btn:saveorder'); ?>
			</th>
			<th width="7%">
				<?php echo JHTML::_('grid.sort', 'ACCESS', 'access', $o['order_Dir'], $o['order']); ?>
			</th>
			<th class="title" width="7%" nowrap="nowrap">
				<?php echo JText::_('TYPE'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
<?php

$k	= 0;
for ($i = 0; $i < $n; $i++)
{
	$b		= & $this->btns[$i];
	
	// Ordering
	$up		= $this->page->orderUpIcon($i, true, 'orderup');
	$down	= $this->page->orderDownIcon($i, $n, true, 'orderdown');
	
	// Icon
	$icon	= $b->icon;
	if (strlen($b->icon))
	{
		$http	= strpos($b->icon, 'http') === 0;
		if (strpos($icon, '/') !== false && !$http) {
			$icon	= JURI::root() . $icon;
		}
		elseif (!$http)
		{
			$uri	= & JFactory::getURI();
			$icon	= $uri->toString(array('scheme','host','port')) . UBAR_ASSETS .'img/icons/'. $icon;
		}
		
		$icon	= JHTML::image($icon, '') .' &nbsp; ';
	}
	
	// Edit link
	$type	= $b->params->get('type');
	$link	= JHTML::link(index .'&task=btn:edit&type='. $type .'&bid[]='. $b->id, $b->name);
	
	// Position
	$pos	= (int) $b->params->get('_pos', 0);
	$pos	= $pos == 1 ? JText::_('RIGHT') : JText::_('LEFT');
?>
		<tr class="row<?php echo $k; ?>">
			<td align="center">
				<?php echo JHTML::_('grid.id', $i, $b->id, false, 'bid'); ?>
			</td>
			<td>
				<?php echo $icon . $link .' ('. $pos .')'; ?>
			</td>
			<td align="center">
				<?php echo JHTML::_('grid.published', $b, $i); ?>
			</td>
			<td style="padding-left:30px;" class="order" colspan="2">
				<div style="float:left;margin-top:5px;">
					<input type="text" name="order[]" size="5"
						value="<?php echo $b->ordering; ?>" 
						class="inputbox" style="text-align:center;" />
				</div>
				<div style="float:left;">
					<div><?php echo $up; ?></div>
					<div><?php echo $down; ?></div>
				</div>
			</td>
			<td align="center">
				<?php echo JHTML::_('grid.access', $b, $i); ?>
			</td>
			<td align="center">
				<?php echo JText::_('BT '. $type); ?>
			</td>
		</tr>
<?php
	$k	= 1 - $k;
}
?>
	</tbody>
	</table>
</div>

<?php
}
?>

	<input type="hidden" name="option" value="com_ubar"/>
	<input type="hidden" name="controller" value="btn"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $o['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $o['order_Dir']; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
