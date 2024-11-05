<?php
/* 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *   Project: BrightDSMS
 *               
 *   ▸ Version: 1.0                                          
 *   ▸ Author : Carter Kenwell       
 *   ▸ Date   : 2024-11-01
 *   ▸ File   : refresh-status.php
 *   ▸ Description: Client refresh check endpoint     
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 */
$refreshFile = 'refresh.json';

$sn = $_GET['sn'];

$refreshFlag = false;

if (file_exists($refreshFile)) {

    $refreshData = json_decode(file_get_contents($refreshFile), true);
    
    if (isset($refreshData[$sn])) {
        $refreshFlag = $refreshData[$sn] === 'true';
    }

}


header('Content-Type: application/json');
echo json_encode(['refresh' => $refreshFlag]);