<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');


class JutModelConfig extends JutModel
{
	function JutModelConfig()
	{
		parent::__construct();
		
		
	}
	
	// Preview buttons
	function getPreviewButtons()
	{
		jimport('joomla.filesystem.folder');
		
		// Check icons folder
		$icons	= array();
		$folder	= JUT_ICONS_PATH;
		if (!is_dir($folder)) {
			return $icons;
		}
		
		// Get some icons
		$files	= JFolder::files($folder, '\.(gif|jpg|jpeg|png|GIF|JPG|JPEG|PNG)', false, false);
		if (!$files || empty($files)) {
			return $icons;
		}
		
		shuffle($files);
		for ($i = 0; $i < count($files), $i < 8; $i++)
		{
			$icons[]	= array(
				'alt'	=> $files[$i],
				'src'	=> JURI::root() .'components/com_jut/assets/img/icons/'. $files[$i]
			);
		}
		
		return $icons;
	}
	
	// Save parameters
	function save()
	{
		// Table, parameters
		$table	= & $this->getParams(true);
		$params	= & $this->getParams(false);
		
		// New parameters
		$np	= JRequest::getVar('params', array(), 'POST', 'array');
		$params->loadArray($np);
		if ($params->get('menus') != 2) {
			$params->set('mis', array());
		}
		
		// Encode CSS
		if (strlen($params->get('css', ''))) {
			$params->set('css', base64_encode($params->get('css')));
		}
		
		// Save parameters
		$table->params	= $params->toString();
		if (!$table->store()) {
			return $this->setError($table->getError());
		}
		
		return true;
	}
}

