<?php

require 'loader.php';

if ($_POST) {
    if ($_POST['options']['submit'] == 'scan') {
        // Scanning of updates catalogs to compare with local repository
        foreach ($categories as $category) {
            $params = getParams($category, $_POST['options']);
            foreach ($params['versions'] as $version) {
                $params['version'] = trim($version);
                $report[$category][$version] = searchForUpdates($params);
            }
            //debug($report);
        }
    } elseif ($_POST['options']['submit'] == 'download') {
        // Downloading of new updates and deleting of old files
        foreach ($categories as $category) {
            $params = getParams($category, $_POST['options']);
            foreach ($params['versions'] as $version) {
                $params['version'] = trim($version);
                $report[$category][$version] = update($params);
            }
            //debug($report);
        }
        
        if (isset($_POST['pack_files'])
            && $_POST['pack_files']
        ) {
            $compress = isset($_POST['compress_packed_files']) && $_POST['compress_packed_files'] ? true : false;
            packFiles(null, $compress);
        }
    }
}

// Rendering of view
render('content', array(
    'config' => $config,
    'report' => $report,
));
