<?php

/**
 * Default values, don't modify ! Change this values in config.php instead.
 */
return array(
    'mirrorPluginsUrl' => 'http://mirror.local/netbeans/plugins/{version}/',
    'mirrorCertifiedUrl' => 'http://mirror.local/netbeans/updates/{version}/certified/',
    'mirrorDistributionUrl' => 'http://mirror.local/netbeans/updates/{version}/distribution/',
    'pluginsTargetDir' => './netbeans/plugins/{version}/',
    'pluginsArchiveDir' => './netbeans/plugins/{version}/archives/',
    'certifiedTargetDir' => './netbeans/updates/{version}/certified/',
    'certifiedArchiveDir' => './netbeans/updates/{version}/certified/archives/',
    'distributionTargetDir' => './netbeans/updates/{version}/distribution/',
    'distributionArchiveDir' => './netbeans/updates/{version}/distribution/archives/',
    'updatePlugins' => true,
    'updateCertified' => true,
    'updateDistribution' => true,
    'archiveOldFiles' => false,
);
