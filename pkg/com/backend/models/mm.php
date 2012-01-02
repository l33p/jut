<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');


class UbarModelMm extends UbarModel
{
	function &getFormData()
	{
		if (!isset($this->_fd))
		{
			$i	= new JObject();
			$s	= & JFactory::getSession();
			$i->set('basepath', $this->getBasePath());
			$i->set('files', $this->getFiles());
			$i->set('uploadURL', JURI::base() . index .'&task=file:upload&'. JUtility::getToken() .'=1&'. $s->getName() .'='. $s->getId());
			$i->set('deleteURL', JURI::base() . index .'&task=file:delete&'. JUtility::getToken() .'=1&'. $s->getName() .'='. $s->getId() .'&file=');
			$this->_fd	= $i;
		}
		
		$ref	= & $this->_fd;
		return $ref;
	}
	
	function getFiles()
	{
		// Get files
		$files	= array();
		jimport('joomla.filesystem.folder');
		$list	= JFolder::files($this->getBasePath(), '\.(bmp|gif|jpg|jpeg|png)', false, true);
		if (empty( $list )) {
			return $files;
		}
		
		// List files
		jimport('joomla.filesystem.file');
		foreach ($list as $filename)
		{
			$f	= new JObject();
			$f->name	= JFile::getName( $filename );
			$f->path	= $filename;
			if (strlen(JPATH_ROOT)) {
				$f->src		= JString::str_ireplace(JPATH_ROOT.DS, JURI::root(), $f->path);
			} else {
				$f->src	= JURI::root() . $f->path;
			}
			$f->src		= str_replace(DS, '/', $f->src);
			$f->size	= $this->parseSize( $f->path );
			$f->ext		= strtolower(JFile::getExt($f->name));
			
			list($w, $h)	= @getimagesize( $f->path );
			list($f->width, $f->height)	= $this->resize($w, $h);
			$files[]	= $f;
		}
		
		return $files;
	}
	
	// See "administrator/components/com_media/helpers/media.php"
	function parseSize( $size )
	{
		if (!is_numeric( $size )) {
			if (!is_string( $size ) || !is_file( $size )) {
				return '?';
			} else {
				$size	= filesize( $size );
			}
		}
		if ($size < 1024) {
			return $size . ' bytes';
		}
		else
		{
			if ($size >= 1024 && $size < 1024 * 1024) {
				return sprintf('%01.2f', $size / 1024.0) . ' KB';
			} else {
				return sprintf('%01.2f', $size / (1024.0 * 1024)) . ' MB';
			}
		}
	}
	
	// See "administrator/components/com_media/helpers/media.php"
	function resize($width, $height, $target = 24)
	{
		// takes the larger size of the width and height and applies the
		// formula accordingly...this is so this script will work
		// dynamically with any size image
		if ($width > $height) {
			$percentage	= ($target / $width);
		} else {
			$percentage	= ($target / $height);
		}
		
		// gets the new value and applies the percentage, then rounds the value
		$width	= ($width > $target) ? round($width * $percentage) : $width;
		$height	= ($height > $target) ? round($height * $percentage) : $height;
		
		return array($width, $height);
	}
	
	function getBasePath() {
		return JPATH_COMPONENT_SITE.DS.'assets'.DS.'img'.DS.'icons';
	}
}

