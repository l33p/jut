<?php
defined('_JEXEC') or die;

/*
 * Defines
 */
define('JUT_NAME', 'Bumble Toolbar');
define('JUT_VERSION', '100');
define('JUT_VERSION_PATCH', '-');
define('JUT_VERSION_READ', '1.0');
define('JUT_VERSION_INC', ((@$_SERVER['HTTP_HOST'] == 'localhost' || @$_SERVER['SERVER_NAME'] == 'localhost') ? time() : JUT_VERSION . JUT_VERSION_PATCH));

/*
 * Paths
 */
define('INDEX', 'index.php?option=com_jut');
define('index', INDEX);
define('JUT_ICONS_PATH', JPATH_SITE.DS.'components'.DS.'com_jut'.DS.'assets'.DS.'img'.DS.'icons');
define('JUT_CLASSES', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jut'.DS.'classes');

/*
 * Folders
 */
define('JUT_ASSETS', JURI::base(true) .'/components/com_jut/assets/');

