<?php

    $file=$_POST['file'];
    $file='backup.json';
    
    header("Content-type: Application/octet-stream");
    header("Content-Disposition: attachment; filename=backup.json");
    header("Content-Description: Download PHP");
    header("Content-Length:".filesize($file));
    readfile($file);

   // unlink($file);


?>