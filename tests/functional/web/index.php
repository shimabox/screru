<?php
require_once realpath(dirname(__FILE__) . '/.init.php');
$detect = new Mobile_Detect();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>test page</title>
</head>
<body>
    <h1 id="welcome">Test</h1>
    <?php if (! $detect->isMobile()) { ?><p id="pc">PC</p><?php } ?>
    <?php if ($detect->isMobile()) { ?><p id="sp">SP</p><?php } ?>
</body>
</html>