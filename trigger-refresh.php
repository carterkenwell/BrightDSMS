<?php
/* 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *   Project: BrightDSMS
 *               
 *   â–¸ Version: 1.0                                          
 *   â–¸ Author : Carter Kenwell       
 *   â–¸ Date   : 2024-12-17 (updated)
 *   â–¸ File   : trigger-refresh.php
 *   â–¸ Description: Administrative refresh endpoint   
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 */

$refreshFile = 'refresh.json';
$message = ''; // Feedback message variable

// Retrieve serial number from the URL
$sn = isset($_GET['sn']) ? $_GET['sn'] : 'unknown';
$sn = preg_replace('/[^A-Za-z0-9]/', '', $sn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trigger_refresh'])) {
    $refreshData = [];

    // Read refresh data to variable
    if (file_exists($refreshFile)) {
        $fileContent = file_get_contents($refreshFile);
        $refreshData = json_decode($fileContent, true);

        if ($refreshData === null) {
            $message = "Failed to decode JSON from $refreshFile. Error: " . json_last_error_msg();
        }
    }

    // Set refresh flag for the serial number
    $refreshData[$sn] = 'true';

    if (file_put_contents($refreshFile, json_encode($refreshData)) === false) {
        $message = 'Failed to set refresh flag. Check file permissions.';
    } else {
        // Use post/return/get (PRG) pattern: Redirect to the same page to avoid resubmission
        header("Location: trigger-refresh.php?sn=" . urlencode($sn) . "&success=1");
        exit;
    }
}

// Check if site redirected with success. If it did, the refresh will then have also triggered successfully given no other errors were displayed/caught.
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = 'Refresh triggered successfully.';
}
//End of PHP, basic HTML elements below.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger Refresh</title>
    <script>
        // Remove the "success" parameter from the URL after the success message is displayed so that the website can be refreshed without resubmitting the refresh trigger POST.
        window.onload = function() {
            if (window.location.search.includes('success=1')) {
                const url = new URL(window.location.href);
                url.searchParams.delete('success');
                window.history.replaceState({}, document.title, url);
            }
        };
    </script>
</head>
<body>
    <h1>Trigger Refresh</h1>

    <?php if ($sn === 'unknown'): ?>
        <p>Endpoint name not provided. Please include it in the URL as ?sn=[Serial Number]</p>
    <?php else: ?>
        <p>Endpoint: <strong><?php echo htmlspecialchars($sn); ?></strong></p>
        <form method="POST" action="trigger-refresh.php?sn=<?php echo urlencode($sn); ?>">
            <button type="submit" name="trigger_refresh">Trigger Refresh</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
</body>
</html>
