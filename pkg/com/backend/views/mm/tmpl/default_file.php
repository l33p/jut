<?php
defined('_JEXEC') or die;

// File details
$f	= & $this->_file;
$icon	= array('width' => $f->width, 'height' => $f->height);
$icon	= JHTML::image($f->src, $f->name, $icon);
$title	= $f->name .'::('. $f->size .')';
$src	= JString::str_ireplace(JURI::root(), '', $f->src);
$file	= base64_encode($f->name);
$click	= 'return selectIcon(\''. $src .'\',\''. $file .'\');';
?>
<div class="item">
	<div align="center" class="border hasTip" title="<?php echo $title; ?>" onclick="<?php echo $click; ?>">
		<a><?php echo $icon; ?></a>
	</div>
</div>
