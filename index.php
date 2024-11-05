<?php
/* 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *   Project: BrightDSMS
 *               
 *   ▸ Version: 1.0                                          
 *   ▸ Author : Carter Kenwell       
 *   ▸ Date   : 2024-11-01
 *   ▸ File   : index.php
 *   ▸ Description: Client-side digital-signage display interface     
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 */

$endpointFile = 'endpoints.json';

//loads endpoints
if (file_exists($endpointFile)) {
    $fileContent = file_get_contents($endpointFile);
    $iframeLink = json_decode($fileContent, true);

    if ($iframeLink === null) {
        echo "Failed to decode JSON from $iframeLink. Error: " . json_last_error_msg();
        exit;
    }
}

//logic for client to request the proper endpoint based on URL input. Redirects to the 404 endpoint if invalid URL is entered.
if (isset($_GET['sn'])) {
    $sn = $_GET['sn'];
    $sn = isset($_GET['sn']) ? $_GET['sn'] : 'unknown';
    $contentLink = isset($iframeLink[$sn]) ? $iframeLink[$sn] : $iframeLink[404];
        
} else {
    $contentLink = $iframeLink[404];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrightDSMS Client</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: black;
        }
        iframe {
            width: 100vw;
            height: 100vh;
            border: none;
        }
    </style>
</head>
<body>
    <script>
        //checks the refresh status of whichever endpoint has been selected in the base URL, if true, reload.
        setInterval(function() {
            fetch('/BrightDSMS/refresh-status.php?sn=<?php echo htmlspecialchars($sn); ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.refresh === true) {
                        window.location.reload();
                    }
                });
        }, 5000);  // Timer
    </script>
    <iframe src="<?php echo htmlspecialchars($contentLink);?>" frameborder="0" width="1920" height="1080" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
</body>
</html>

<?php
//prepares client to return refresh status back to false after triggering the reload of the iframe
$refreshFile = 'refresh.json';
$sn = preg_replace('/[^A-Za-z0-9]/', '', $sn);
$refreshData = [];

if (file_exists($refreshFile)) {
    $refreshData = json_decode(file_get_contents($refreshFile), true);
}

if (isset($refreshData[$sn])) {
    sleep(6);
    $refreshData[$sn] = 'false'; 
    file_put_contents($refreshFile, json_encode($refreshData));
}
?>