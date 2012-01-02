<?php
defined('_JEXEC') or die;



// Build cPanel
echo '<div id="cpanel">';

// Global Configuration
echo $this->cPanelButton(
	'Global Configuration',
	//'templates/bluestork/images/header/icon-48-config.png',
	JUT_ASSETS .'img/setting_tools.png',
	'view:config'
);

// Buttons list
echo $this->cPanelButton(
	'Buttons List',
	//'templates/bluestork/images/header/icon-48-frontpage.png',
	JUT_ASSETS .'img/interface_preferences.png',
	''
);

// Add button
echo $this->cPanelButton(
	'Add button',
	JUT_ASSETS .'img/book_add.png',
	''
);

// Default buttons
echo $this->cPanelButton(
	'Default Buttons',
	//'templates/bluestork/images/header/icon-48-install.png',
	JUT_ASSETS .'img/wishlist_add.png',
	''
);

// Import
echo $this->cPanelButton(
	'Import/Export',
	JUT_ASSETS .'img/document_export.png',
	''
);

// Health check
echo $this->cPanelButton(
	'Health Check',
	//'templates/bluestork/images/header/icon-48-clear.png',
	JUT_ASSETS .'img/battery_charge.png',
	'view:health'
);


// End cPanel
echo '</div>';

