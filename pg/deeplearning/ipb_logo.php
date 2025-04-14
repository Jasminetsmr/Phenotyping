<?php
// This file serves the IPB logo directly from the filesystem
header('Content-Type: image/png');
readfile($_SERVER['DOCUMENT_ROOT'] . '/ds_min/dist/img/ipb.png');
?>
