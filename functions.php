<?php

/**
 * Fonction de debug
 */
function debug($objet = 'OK', $detail = false, $condition = true)
{    
    if ($condition) {
        echo '<div style="background: ghostwhite; border: 2px solid lightgrey; padding: 10px;">';
        echo '<h1 style="font-family: Arial; font-size: 16px; font-weight: bold; margin: 0; padding: 0; color: red;">DEBUG</h1>';
        echo '<pre>';
        if (!$detail) {
            if ($objet === null) {
                echo 'null';
            } else {
                print_r($objet);
            }
        } else {
            var_dump($objet);
        }
        echo '</pre>';
        echo '</div>';
        exit();
    }
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('o', 'Kio', 'Mio', 'Gio', 'Tio'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
}

function toAbsolutePath($moduleUrl, $version, $modulesUrl)
{
	if (substr($moduleUrl, 0, 7) !== 'http://') {
		$moduleUrl = str_replace('{version}', $version, $modulesUrl) . $moduleUrl;
	}
	return $moduleUrl;
}

/**
 * Extraction du nom du fichier
 */
function extractFileName($path, $modulesUrl)
{
	$separator = strrpos($path, '/');
	$ret['folder'] = substr($path, 0, $separator + 1);
    if (substr($ret['folder'], 0, strlen($modulesUrl)) == $modulesUrl) {
        $ret['folder'] = substr($ret['folder'], strlen($modulesUrl));
    }
	$ret['filename'] = substr($path, 1 - (strlen($path) - $separator));
	return $ret;
}

function getCatalog($catalogUrl, $version)
{
	$downloadPath = str_replace('{version}', $version, $catalogUrl);
	
	// Lecture du fichier de plugins
	$catalog = file_get_contents($downloadPath);
	if ($catalog === false) {
		die('Catalogue inaccessible !');
	}
	
	return $catalog;
}

function getModule($xmlModule, $modulesUrl)
{
	$module = array();
	$module['codename'] = (string) $xmlModule['codenamebase'];
	$module['name'] = (string) $xmlModule->manifest['OpenIDE-Module-Name'];
	$module['releaseDate'] = (string) $xmlModule['releasedate'];
	$module['size'] = (int) $xmlModule['downloadsize'];
	$module['url'] = (string) $xmlModule['distribution'];
	$module['file'] = extractFileName($module['url'], $modulesUrl);
	
	return $module;
}

function getModuleGroup($xmlModuleGroup, $modulesUrl)
{
	$moduleGroup = array();
	$moduleGroup['name'] = (string) $xmlModuleGroup['name'];
	
	foreach ($xmlModuleGroup->module as $xmlModule) {
		$moduleGroup['modules'][] = getModule($xmlModule, $modulesUrl);
	}
	
	return $moduleGroup;
}

function transformPath($chaine, $version)
{
    return str_replace('{version}', $version, $chaine);
}

function update($params)
{
	// Initialisation du nom du répertoire de destination
	$catalogDir = transformPath($params['targetDir'], $params['version']);
    $modulesDir = $catalogDir . 'files/';
    if ($params['archiveModules']) {
        $archiveDir = transformPath($params['archiveDir'], $params['version']);
    }
    
	// Récupération du catalogue
	$catalog = getCatalog($params['catalogUrl'], $params['version']);
	
	// Le catalogue existe, on crée l'arborescence
    if (!is_dir($catalogDir)) {
		mkdir($catalogDir);
	}
	if (!is_dir($modulesDir)) {
		mkdir($modulesDir);
	}
    if ($params['archiveModules']
        && !is_dir($archiveDir)
    ) {
		mkdir($archiveDir);
	}
    
	// XMLisation du fichier de plugins
	$xml = simplexml_load_string($catalog);
	//debug($xml);
	
	// Recherche des modules
	$moduleGroups = array();
	
	// Recherche des modules sans groupes
	// On les place dans un tableau 'no_group'
	foreach ($xml->module as $xmlModule) {
		$moduleGroups['no_group']['modules'][] = getModule($xmlModule, $params['modulesUrl']);
	}
	
	// Recherche des groupes de modules
	foreach ($xml->module_group as $xmlModuleGroup) {
		$moduleGroups[] = getModuleGroup($xmlModuleGroup, $params['modulesUrl']);
	}
	
	// Récupération de la liste des plugins déjà téléchargés
    if ($params['archiveModules']) {
        $existingPlugins = glob($modulesDir . '*.{nbm,jar}', GLOB_BRACE);
    }
	//debug($existingPlugins);
	
	// Téléchargement des plugins
	$plugins = array();
	$plugins['downloaded'] = array();
	$plugins['not_downloaded'] = array();
	$plugins['seen'] = array();
	$plugins['removed'] = array();
	foreach ($moduleGroups as $moduleGroup) {
		foreach ($moduleGroup['modules'] as $module) {
			// Si le plugin existe déjà, on ne le télécharge pas
			if (!file_exists($modulesDir . $module['file']['folder'] . $module['file']['filename']) ||
                filesize($modulesDir . $module['file']['folder'] . $module['file']['filename']) != $module['size']
            ) {
                if (!is_dir($modulesDir . $module['file']['folder'])) {
                    mkdir($modulesDir . $module['file']['folder'], 0777, true);
                }
				$ret = copy(toAbsolutePath($module['url'], $params['version'], $params['modulesUrl']), $modulesDir . $module['file']['folder'] . $module['file']['filename']);
				if (!$ret) {
					echo 'La copie du fichier ' . $module['file']['folder'] . $module['file']['filename'] . ' a échoué !' . PHP_EOL;
				}
				$plugins['downloaded'][] = $module;
			} else {		
				$plugins['not_downloaded'][] = $module;
			}
			$plugins['seen'][] = $module['file']['folder'] . $module['file']['filename'];
		}
	}
	
	// Détermination des plugins qui ne sont plus dans le catalogue
    if ($params['archiveModules']) {
        foreach ($existingPlugins as $existingPlugin) {
            $existingPlugin = extractFileName($existingPlugin, $params['modulesUrl']);
            if (!in_array($existingPlugin['filename'], $plugins['seen'])) {
                $plugins['removed'][] = $existingPlugin['filename'];
            }
        }
    }
    
	// Archivage ou suppression des plugins obsolètes
    if ($params['archiveModules']) {
        foreach ($plugins['removed'] as $plugin) {
            rename($modulesDir . $plugin, $archiveDir . $plugin);
        }
    } else {
        foreach ($plugins['removed'] as $plugin) {
            unlink($modulesDir . $plugin);
        }
    }
	
	//debug($plugins);	

	// Création du catalogue du miroir
    $pluginsUrl = transformPath($params['modulesUrl'], $params['version']);
    $mirrorPluginsUrl = transformPath($params['mirrorUrl'], $params['version']);
	$newCatalog = str_replace($pluginsUrl, $mirrorPluginsUrl . 'files/', $catalog);
	file_put_contents($catalogDir . 'catalog.xml', $newCatalog);
	
	return $plugins;
}

function searchForUpdates($params)
{
    // Initialisation du nom du répertoire de destination
	$catalogDir = transformPath($params['targetDir'], $params['version']);
    $modulesDir = $catalogDir . 'files/';
    if ($params['archiveModules']) {
        $archiveDir = transformPath($params['archiveDir'], $params['version']);
    }
    
	// Récupération du catalogue
	$catalog = getCatalog($params['catalogUrl'], $params['version']);
    
	// XMLisation du fichier de plugins
	$xml = simplexml_load_string($catalog);
	//debug($xml);
	
	// Recherche des modules
	$moduleGroups = array();
	
	// Recherche des modules sans groupes
	// On les place dans un tableau 'no_group'
	foreach ($xml->module as $xmlModule) {
		$moduleGroups['no_group']['modules'][] = getModule($xmlModule, $params['modulesUrl']);
	}
	
	// Recherche des groupes de modules
	foreach ($xml->module_group as $xmlModuleGroup) {
		$moduleGroups[] = getModuleGroup($xmlModuleGroup, $params['modulesUrl']);
	}
	
	// Récupération de la liste des plugins déjà téléchargés
    if ($params['archiveModules']) {
        $existingPlugins = glob($modulesDir . '*.{nbm,jar}', GLOB_BRACE);
    }
	//debug($existingPlugins);
	
	// Téléchargement des plugins
	$plugins = array();
	$plugins['downloaded'] = array();
	$plugins['not_downloaded'] = array();
	$plugins['seen'] = array();
	$plugins['removed'] = array();
	foreach ($moduleGroups as $moduleGroup) {
		foreach ($moduleGroup['modules'] as $module) {
			// Si le plugin existe déjà, on ne le télécharge pas
			if (!file_exists($modulesDir . $module['file']['folder'] . $module['file']['filename']) ||
                filesize($modulesDir . $module['file']['folder'] . $module['file']['filename']) != $module['size']
            ) {
				$plugins['downloaded'][] = $module;
			} else {		
				$plugins['not_downloaded'][] = $module;
			}
			$plugins['seen'][] = $module['file']['folder'] . $module['file']['filename'];
		}
	}
	
	// Détermination des plugins qui ne sont plus dans le catalogue
    if ($params['archiveModules']) {
        foreach ($existingPlugins as $existingPlugin) {
            $existingPlugin = extractFileName($existingPlugin, $params['modulesUrl']);
            if (!in_array($existingPlugin['filename'], $plugins['seen'])) {
                $plugins['removed'][] = $existingPlugin['filename'];
            }
        }
    }
	
	//debug($plugins);	
	
	return $plugins;
}

function getParams($category, $options)
{
    $params = array();
    if ($category == 'plugins') {
        $params['archiveModules'] = isset($options['archive_old_files']) ? $options['archive_old_files'] : false;
        $params['catalogUrl'] = PLUGINS_CATALOG_URL;
        $params['targetDir'] = $options['plugins_target_dir'];
        $params['archiveDir'] = $options['plugins_archive_dir'];
        $params['modulesUrl'] = PLUGINS_URL;
        $params['mirrorUrl'] = $options['mirror_plugins_url'];
        $params['versions'] = extractVersionNumbers($options["plugins_versions"]);
    } elseif ($category == 'certified') {
        $params['archiveModules'] = false;
        $params['catalogUrl'] = CERTIFIED_CATALOG_URL;
        $params['targetDir'] = $options['certified_target_dir'];
        $params['archiveDir'] = $options['certified_archive_dir'];
        $params['modulesUrl'] = CERTIFIED_URL;
        $params['mirrorUrl'] = $options['mirror_certified_url'];
        $params['versions'] = extractVersionNumbers($options["netbeans_versions"]);
    } elseif ($category == 'distribution') {
        $params['archiveModules'] = false;
        $params['catalogUrl'] = DISTRIBUTION_CATALOG_URL;
        $params['targetDir'] = $options['distribution_target_dir'];
        $params['archiveDir'] = $options['distribution_archive_dir'];
        $params['modulesUrl'] = DISTRIBUTION_URL;
        $params['mirrorUrl'] = $options['mirror_distribution_url'];
        $params['versions'] = extractVersionNumbers($options["netbeans_versions"]);
    }
    
    return $params;
}

/**
 * On remplace les \r\n de manière à être certain de bien "explode" la chaîne
 * des numéros de versions
 * 
 * @param type $string
 * @return type
 */
function extractVersionNumbers($string)
{
    $string = str_replace("\r\n", "\n", $string);
    return explode("\n", $string);
}

function render($file, $options)
{
    foreach ($options as $key => $value) {
        $$key = $value;
    }
    
    ob_start();
    require "views/$file.php";
    $content = ob_get_clean();
    
    require 'views/layout.php';
}

function renderPartial($file, $options)
{
    foreach ($options as $key => $value) {
        $$key = $value;
    }
    
    ob_start();
    require "views/$file.php";
    return ob_get_clean();
}