<?php
$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

$ds = DIRECTORY_SEPARATOR;
return array(
    'IPHP' => array($vendorDir . $ds . 'IPHP'. $ds .'framework'. $ds .'IPHP'),
    'Breadcrumbs' => array($vendorDir . $ds . 'Breadcrumbs'. $ds .'src'),
    'App' => array($baseDir . $ds . 'platform' . $ds . 'app'),
);