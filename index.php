<?php

// URL of Netbeans catalogs. Don't need to modify this.
define('PLUGINS_CATALOG_URL', 'http://plugins.netbeans.org/nbpluginportal/updates/{version}/catalog.xml');
define('CERTIFIED_CATALOG_URL', 'http://updates.netbeans.org/netbeans/updates/{version}/uc/final/certified/catalog.xml');
define('DISTRIBUTION_CATALOG_URL', 'http://updates.netbeans.org/netbeans/updates/{version}/uc/final/distribution/catalog.xml');

// URL of Netbeans plugins and updates. Don't need to modify this.
define('PLUGINS_URL', 'http://plugins.netbeans.org/nbpluginportal/files/nbms/');
define('CERTIFIED_URL', 'http://updates.netbeans.org/netbeans/updates/{version}/uc/final/certified/');
define('DISTRIBUTION_URL', 'http://updates.netbeans.org/netbeans/updates/{version}/uc/final/distribution/');

// Your mirror URL. That will be the URL where catalogs will be placed. Modify
// to your needs.
define('MIRROR_PLUGINS_URL', 'http://miroir.local/netbeans/plugins/{version}/');
define('MIRROR_CERTIFIED_URL', 'http://miroir.local/netbeans/updates/{version}/certified/');
define('MIRROR_DISTRIBUTION_URL', 'http://miroir.local/netbeans/updates/{version}/distribution/');

// Directories where plugins and updates will be downloaded. You can use either
// absolute or relatives paths.
define('PLUGINS_TARGET_DIR', './netbeans/plugins/{version}/');
define('PLUGINS_ARCHIVE_DIR', './netbeans/plugins/{version}/archives/');
define('CERTIFIED_TARGET_DIR', './netbeans/updates/{version}/certified/');
define('CERTIFIED_ARCHIVE_DIR', './netbeans/updates/{version}/certified/archives/');
define('DISTRIBUTION_TARGET_DIR', './netbeans/updates/{version}/distribution/');
define('DISTRIBUTION_ARCHIVE_DIR', './netbeans/updates/{version}/distribution/archives/');

// Booleans to determine if plugins and updates will be downloaded.
define('UPDATE_PLUGINS', false);
define('UPDATE_CERTIFIED', true);
define('UPDATE_DISTRIBUTION', true);
define('ARCHIVE_OLD_FILES', true);

require 'functions.php';

$report = array();
$report['plugins'] = array();
$report['certified'] = array();
$report['distribution'] = array();

// Versions de Netbeans dont on veut récupérer les plugins
$pluginsVersions = array(
	//'7.0',
	//'7.1',
	//'7.2',
	'7.3',
	'7.4',
	'8.0',
	'8.1',
);

// Versions de Netbeans dont on veut récupérer les updates
$netbeansVersions = array(
    '7.3',
    //'7.3.1',
    '7.4',
    //8.0.1,
    '8.0.2',
    '8.1',
);

// Le traitement de téléchargement prend beaucoup de temps...
set_time_limit(0);

// Récupération des plugins
// On lance le traitement pour chaque version
if (UPDATE_PLUGINS) {
    $params = array();
    $params['archiveModules'] = ARCHIVE_OLD_FILES;
    $params['catalogUrl'] = PLUGINS_CATALOG_URL;
    $params['targetDir'] = PLUGINS_TARGET_DIR;
    $params['archiveDir'] = PLUGINS_ARCHIVE_DIR;
    $params['modulesUrl'] = PLUGINS_URL;
    $params['mirrorUrl'] = MIRROR_PLUGINS_URL;
    foreach ($pluginsVersions as $version) {
        $params['version'] = $version;
        $report['plugins'][$version] = update($params);
    }
}

// Récupération des updates "certified"
// On lance le traitement pour chaque version
if (UPDATE_CERTIFIED) {
    $params = array();
    $params['archiveModules'] = false;
    $params['catalogUrl'] = CERTIFIED_CATALOG_URL;
    $params['targetDir'] = CERTIFIED_TARGET_DIR;
    $params['archiveDir'] = CERTIFIED_ARCHIVE_DIR;
    $params['modulesUrl'] = CERTIFIED_URL;
    $params['mirrorUrl'] = MIRROR_CERTIFIED_URL;
    foreach ($netbeansVersions as $version) {
        $params['version'] = $version;
        $report['certified'][$version] = update($params);
    }
}

// Récupération des updates "certified"
// On lance le traitement pour chaque version
if (UPDATE_DISTRIBUTION) {
    $params = array();
    $params['archiveModules'] = false;
    $params['catalogUrl'] = DISTRIBUTION_CATALOG_URL;
    $params['targetDir'] = DISTRIBUTION_TARGET_DIR;
    $params['archiveDir'] = DISTRIBUTION_ARCHIVE_DIR;
    $params['modulesUrl'] = DISTRIBUTION_URL;
    $params['mirrorUrl'] = MIRROR_DISTRIBUTION_URL;
    foreach ($netbeansVersions as $version) {
        $params['version'] = $version;
        $report['certified'][$version] = update($params);
    }
}

// Une fois les téléchargements effectués, on détermine la liste des plugins
// obsolètes


?>
<!DOCTYPE html>
<html>
<head lang="fr">
	<title>Téléchargement des plugins Netbeans</title>
    
    <meta charset="utf-8" />
    
	<style>
		body {
			font-family: "Trebuchet MS";
		}
		
		table {
			border-collapse: collapse;
			font-size: 10px;
		}
		
		table th {
			background: grey;
			color: white;
		}
		
		table, table td {
			border: 1px solid black;
		}
		
		table td.right {
			text-align: right;
		}
	</style>
</head>
<body>
    
<?php foreach ($report['plugins'] as $version => $plugins): ?>
<h1>Netbeans v<?php echo $version; ?></h1>

<h2><?php echo count($plugins['downloaded']); ?> nouveaux plugins</h2>
<table>
	<tr>
		<th>Nom de code</th>
		<th>Nom</th>
		<th>Date de sortie</th>
		<th>Taille</th>
	</tr>
	<?php foreach ($plugins['downloaded'] as $plugin): ?>
	<tr>
		<td><?php echo $plugin['codename']; ?></td>
		<td><?php echo $plugin['name']; ?></td>
		<td><?php echo $plugin['releaseDate']; ?></td>
		<td class="right"><?php echo $plugin['size']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>

<h2><?php echo count($plugins['not_downloaded']); ?> plugins existants</h2>
<table>
	<tr>
		<th>Nom de code</th>
		<th>Nom</th>
		<th>Date de sortie</th>
		<th>Taille</th>
	</tr>
	<?php foreach ($plugins['not_downloaded'] as $plugin): ?>
	<tr>
		<td><?php echo $plugin['codename']; ?></td>
		<td><?php echo $plugin['name']; ?></td>
		<td><?php echo $plugin['releaseDate']; ?></td>
		<td class="right"><?php echo formatBytes($plugin['size']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>

<h2><?php echo count($plugins['removed']); ?> plugins archivés</h2>
<table>
	<tr>
		<th>Nom de code</th>
	</tr>
	<?php foreach ($plugins['removed'] as $plugin): ?>
	<tr>
		<td><?php echo $plugin; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endforeach; ?>

<?php foreach ($report['certified'] as $version => $plugins): ?>
<h1>Netbeans v<?php echo $version; ?></h1>

<h2><?php echo count($plugins['downloaded']); ?> nouveaux plugins</h2>
<table>
	<tr>
		<th>Nom de code</th>
		<th>Nom</th>
		<th>Date de sortie</th>
		<th>Taille</th>
	</tr>
	<?php foreach ($plugins['downloaded'] as $plugin): ?>
	<tr>
		<td><?php echo $plugin['codename']; ?></td>
		<td><?php echo $plugin['name']; ?></td>
		<td><?php echo $plugin['releaseDate']; ?></td>
		<td class="right"><?php echo $plugin['size']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>

<h2><?php echo count($plugins['not_downloaded']); ?> plugins existants</h2>
<table>
	<tr>
		<th>Nom de code</th>
		<th>Nom</th>
		<th>Date de sortie</th>
		<th>Taille</th>
	</tr>
	<?php foreach ($plugins['not_downloaded'] as $plugin): ?>
	<tr>
		<td><?php echo $plugin['codename']; ?></td>
		<td><?php echo $plugin['name']; ?></td>
		<td><?php echo $plugin['releaseDate']; ?></td>
		<td class="right"><?php echo formatBytes($plugin['size']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endforeach; ?>

<?php foreach ($report['distribution'] as $version => $plugins): ?>
<h1>Netbeans v<?php echo $version; ?></h1>

<h2><?php echo count($plugins['downloaded']); ?> nouveaux plugins</h2>
<table>
	<tr>
		<th>Nom de code</th>
		<th>Nom</th>
		<th>Date de sortie</th>
		<th>Taille</th>
	</tr>
	<?php foreach ($plugins['downloaded'] as $plugin): ?>
	<tr>
		<td><?php echo $plugin['codename']; ?></td>
		<td><?php echo $plugin['name']; ?></td>
		<td><?php echo $plugin['releaseDate']; ?></td>
		<td class="right"><?php echo $plugin['size']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>

<h2><?php echo count($plugins['not_downloaded']); ?> plugins existants</h2>
<table>
	<tr>
		<th>Nom de code</th>
		<th>Nom</th>
		<th>Date de sortie</th>
		<th>Taille</th>
	</tr>
	<?php foreach ($plugins['not_downloaded'] as $plugin): ?>
	<tr>
		<td><?php echo $plugin['codename']; ?></td>
		<td><?php echo $plugin['name']; ?></td>
		<td><?php echo $plugin['releaseDate']; ?></td>
		<td class="right"><?php echo formatBytes($plugin['size']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endforeach; ?>
</body>
</html>