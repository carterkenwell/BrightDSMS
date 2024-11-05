<?php
/* 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *   Project: BrightDSMS
 *               
 *   ▸ Version: 1.0                                          
 *   ▸ Author : Carter Kenwell       
 *   ▸ Date   : 2024-11-01
 *   ▸ File   : trigger-refresh.php
 *   ▸ Description: Administrative refresh endpoint   
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 */
$refreshFile = 'refresh.json';

$sn = isset($_GET['sn']) ? $_GET['sn'] : 'unknown';

$sn = preg_replace('/[^A-Za-z0-9]/', '', $sn);

$refreshData = [];

if (file_exists($refreshFile)) {
    $fileContent = file_get_contents($refreshFile);
    $refreshData = json_decode($fileContent, true);

    if ($refreshData === null) {
        echo "Failed to decode JSON from $refreshFile. Error: " . json_last_error_msg();
        exit;
    }
}
$refreshData[$sn] = 'true';

if (file_put_contents($refreshFile, json_encode($refreshData)) === false) {
    echo 'Failed to set refresh flag. Check file permissions.';
} else {
    echo 'Refresh triggered successfully';
}
?>