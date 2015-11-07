<?php

require 'loader.php';

if ($_POST) {
    if ($_POST['options']['submit'] == 'scan') {
        foreach ($categories as $category) {
            $params = getParams($category, $_POST['options']);
            foreach ($params['versions'] as $version) {
                $params['version'] = trim($version);
                $report[$category][$version] = searchForUpdates($params);
            }
            //debug($report);
        }
    } elseif ($_POST['options']['submit'] == 'download') {
        foreach ($categories as $category) {
            $params = getParams($category, $_POST['options']);
            foreach ($params['versions'] as $version) {
                $params['version'] = trim($version);
                $report[$category][$version] = update($params);
            }
            //debug($report);
        }
    }
}

render('content', array(
    'config' => $config,
    'report' => $report,
));
