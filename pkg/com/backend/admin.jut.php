<?php
defined('_JEXEC') or die;


// Base classes, defines
require_once(JPATH_COMPONENT.DS.'defines.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'model.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'view.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'controller.php');
jimport('joomla.filter.filterinput');
jimport('joomla.application.input');

// Get task and controller
// ToDo: how to use JInput::Get
//$task	= JInput::get('task');
$task	= JRequest::getString('task');
if (strpos($task, ':'))
{
	list($cName, $task)	= explode(':', $task);

	// Define the controller name and path
	$cName	= strtolower(JFilterInput::clean($cName, 'WORD'));
	$file	= JPATH_COMPONENT.DS.'controllers'.DS.$cName.'.php';

	if (file_exists( $file )) {
		require_once( $file );
	} else {
		JError::raiseError(500, 'Invalid Controller');
	}
	$cName	= 'JutController'. ucfirst( $cName );
}

//elseif ($cName = JInput::get('controller'))
elseif ($cName = JRequest::getString('controller'))
{
	$cName	= strtolower(JFilterInput::clean($cName, 'WORD'));
	$file	= JPATH_COMPONENT.DS.'controllers'.DS.$cName.'.php';

	if (file_exists( $file )) {
		require_once( $file );
	} else {
		JError::raiseError(500, 'Invalid Controller');
	}
	$cName	= 'JutController'. ucfirst( $cName );
}

else {
	$cName	= 'JutController';
}

// Perform task
$controller = new $cName();
$controller->execute( $task );
$controller->redirect();

