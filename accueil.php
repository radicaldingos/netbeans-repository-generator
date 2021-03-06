<?php

require 'loader.php';

// Le traitement de téléchargement prend beaucoup de temps...
set_time_limit(0);

// Récupération des plugins
// On lance le traitement pour chaque version
if ($config['updatePlugins']) {
    $params = array();
    $params['archiveModules'] = $config['archiveOldFiles'];
    $params['catalogUrl'] = PLUGINS_CATALOG_URL;
    $params['targetDir'] = $config['pluginsTargetDir'];
    $params['archiveDir'] = $config['pluginsArchiveDir'];
    $params['modulesUrl'] = PLUGINS_URL;
    $params['mirrorUrl'] = $config['mirrorPluginsUrl'];
    foreach ($config['pluginsVersions'] as $version) {
        $params['version'] = $version;
        $report['plugins'][$version] = update($params);
    }
}

// Récupération des updates "certified"
// On lance le traitement pour chaque version
if ($config['updateCertified']) {
    $params = array();
    $params['archiveModules'] = false;
    $params['catalogUrl'] = CERTIFIED_CATALOG_URL;
    $params['targetDir'] = $config['certifiedTargetDir'];
    $params['archiveDir'] = $config['certifiedArchiveDir'];
    $params['modulesUrl'] = CERTIFIED_URL;
    $params['mirrorUrl'] = $config['mirrorCertifiedUrl'];
    foreach ($config['netbeansVersions'] as $version) {
        $params['version'] = $version;
        $report['certified'][$version] = update($params);
    }
}

// Récupération des updates "certified"
// On lance le traitement pour chaque version
if ($config['updateDistribution']) {
    $params = array();
    $params['archiveModules'] = false;
    $params['catalogUrl'] = DISTRIBUTION_CATALOG_URL;
    $params['targetDir'] = $config['distributionTargetDir'];
    $params['archiveDir'] = $config['distributionArchiveDir'];
    $params['modulesUrl'] = DISTRIBUTION_URL;
    $params['mirrorUrl'] = $config['mirrorDistributionUrl'];
    foreach ($config['netbeansVersions'] as $version) {
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