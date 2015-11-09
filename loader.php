<?php

// Loading of functions file 
require 'functions.php';

// URL of Netbeans catalogs. Don't need to modify this.
define('PLUGINS_CATALOG_URL', 'http://plugins.netbeans.org/nbpluginportal/updates/{version}/catalog.xml');
define('CERTIFIED_CATALOG_URL', 'http://updates.netbeans.org/netbeans/updates/{version}/uc/final/certified/catalog.xml');
define('DISTRIBUTION_CATALOG_URL', 'http://updates.netbeans.org/netbeans/updates/{version}/uc/final/distribution/catalog.xml');

// URL of Netbeans plugins and updates. Don't need to modify this.
define('PLUGINS_URL', 'http://plugins.netbeans.org/nbpluginportal/files/nbms/');
define('CERTIFIED_URL', 'http://updates.netbeans.org/netbeans/updates/{version}/uc/final/certified/');
define('DISTRIBUTION_URL', 'http://updates.netbeans.org/netbeans/updates/{version}/uc/final/distribution/');

// Categories of Netbeans files
$categories = array(
    'plugins',
    'certified',
    'distribution',
);

// Initialization
$report = array();
foreach ($categories as $category) {
    $report[$category] = array();
}

// Loading configuration
$defaultConfig = (require 'default-config.php');
$userConfig = (require 'config.php');
$config = array_merge($defaultConfig, $userConfig);

// Scanning and downloading take time...
set_time_limit(0);