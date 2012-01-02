<?php
defined('_JEXEC') or die;

/*
 * File list
 */
?>
<div>
	<div id="messages" style="display:none;"></div>
	<div id="fileList">
		<div style="clear:both;"></div>
<?php
if (count($this->form->files))
{
	foreach ($this->form->files as $f)
	{
		$this->_file	= & $f;
		echo $this->loadTemplate('file');
	}
}
?>
		<div style="clear:both;"></div>
	</div>
</div>
<?php
/*
 * Upload form
 */
$del	= JRequest::getBool('delete', false);
$del	= $del ? 'checked="checked"' : '';
?>
<form action="<?php echo $this->form->uploadURL; ?>" id="uploadForm" name="uploadForm" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>
			<?php echo JText::_('UPLOAD'); ?>
			&nbsp;/&nbsp;
			<input type="checkbox" name="deli" <?php echo $del; ?> /> <?php echo JText::_('SELECT AN ITEM TO DELETE'); ?>
			&nbsp;/&nbsp;
			<a href="http://www.famfamfam.com" target="_blank">FAMFAMFAM</a>
		</legend>
		<input type="file" id="file-upload" name="Filedata" />
		<input type="submit" id="file-upload-submit" value="<?php echo JText::_('UPLOAD'); ?>" />
		<span id="upload-clear"></span>
		<span style="margin:0 75px;"> </span>
		
		<ul class="upload-queue" id="upload-queue">
			<li style="display:none" />
		</ul>
	</fieldset>
</form>
