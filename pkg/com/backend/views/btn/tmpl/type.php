<?php
defined('_JEXEC') or die;

// Edit links
$edit	= index .'&task=btn:edit&type=';
?>

<form action="<?php echo index; ?>" method="post" name="adminForm">
<table class="admintable" width="100%">

<tr valign="top">
<td width="60%">
<fieldset>
	<legend>
		<?php echo JText::_('SELECT BTN TYPE'); ?>
	</legend>
	<ul id="menu-item" class="jtree">
		<li><div class="base"><span></span><a href="<?php echo $edit .'link'; ?>"><?php echo JText::_('BT LINK'); ?></a></div></li>
		<li><div class="base"><span></span><a href="<?php echo $edit .'menu'; ?>"><?php echo JText::_('BT MENU'); ?></a></div></li>
		<li><div class="base"><span></span><a href="<?php echo $edit .'mm'; ?>"><?php echo JText::_('BT MM'); ?></a></div></li>
		<li><div class="base"><span></span><a href="<?php echo $edit .'user'; ?>"><?php echo JText::_('BT USER'); ?></a></div></li>
<?php

// Close component node
if (!$this->componentList)
{
?>
		<li><div class="node"><span></span><a href="<?php echo index .'&task=btn:edit&tree=component'; ?>"><?php echo JText::_('BT COMPONENT'); ?></a></div></li>
<?php
}

// Open component node
else
{
?>
		<li><div class="node-open"><span></span><a href="javascript:void(0);"><?php echo JText::_('BT COMPONENT'); ?></a></div>
		<ul>
<?php
	$x	= $this->expansion;
	$cn	= count($this->componentList);
	for ($ci = 0; $ci < $cn; $ci++)
	{
		$c	= & $this->componentList[$ci];
		
		// LI class
		$li		= $ci == ($cn - 1) ? 'class="last"' : '';
		
		// DIV class, link, name
		if ($c->option == $x->option) {
			$div	= 'node-open';
			$href	= 'javascript:void(0);';
			$name	= JText::_($c->name);
		} elseif ($c->legacy) {
			$div	= 'node base';
			$href	= $edit .'component&url[option]='. $c->option;
			$name	= $c->name;
		} else {
			$div	= 'node';
			$href	= index .'&task=btn:edit&expand='. $c->option;
			$name	= JText::_($c->name);
		}
?>
			<li <?php echo $li; ?>>
			<div class="<?php echo $div; ?>">
				<a href="<?php echo $href; ?>"><?php echo $name; ?></a>
			</div>
<?php
		if ($c->option == $x->option && $x->tree) {
			echo $x->tree->render();
		}
?>
			</li>
<?php
	}
?>
		</ul>
		</li>
<?php
}

// Close module node
if (!$this->moduleList)
{
?>
		<li><div class="node"><span></span><a href="<?php echo index .'&task=btn:edit&tree=module'; ?>"><?php echo JText::_('BT MODULE'); ?></a></div></li>
<?php
}

// Open module node
else
{
?>
		<li><div class="node-open"><span></span><a href="javascript:void(0);"><?php echo JText::_('BT MODULE'); ?></a></div>
		<ul>
<?php
	$mn	= count($this->moduleList);
	for ($mi = 0; $mi < $mn; $mi++)
	{
		$m	= & $this->moduleList[$mi];
		
		// LI class
		$li	= $mi == ($mn - 1) ? 'class="last"' : '';
		
		// DIV class, link
		if ($m->module == $x->module) {
			$div	= 'node-open';
			$href	= 'javascript:void(0);';
		} else {
			$div	= 'node base';
			$href	= $edit .'module&module='. $m->module;
		}
?>
			<li <?php echo $li; ?>>
			<div class="<?php echo $div; ?>">
				<a href="<?php echo $href; ?>" class="hasTip" title="<?php echo $m->name .'::'. $m->descrip; ?>"><?php echo $m->name; ?></a>
			</div>
			</li>
<?php
	}
?>
		</ul>
		</li>
<?php
}
?>
		<li><div class="base"><span></span><a href="<?php echo $edit .'pad'; ?>"><?php echo JText::_('BT PAD'); ?></a></div></li>
		<li><div class="base"><span></span><a href="<?php echo $edit .'search'; ?>"><?php echo JText::_('BT SEARCH'); ?></a></div></li>
		<li><div class="base"><span></span><a href="<?php echo $edit .'html'; ?>">HTML</a></div></li>
		<li class="last"><div class="base"><span></span><a href="<?php echo $edit .'custom'; ?>"><?php echo JText::_('BT CUSTOM'); ?></a></div></li>
	</ul>
</fieldset>
</td>
<td width="40%"></td>
</tr>
</table>

	<input type="hidden" name="option" value="com_ubar" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>