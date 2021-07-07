<?php

// Usage example without composer 

require './src/wgd.php';

if (isset($_GET['download'])) {
    $conf = [
        "filename" => "NewFileName.mp4",
        "folder" => "YOUR FOLDER",
        "url" => "YOUR URL"
    ];
    $file = new maestroerror\wgd($conf);
    $file->silent()->speedLimit("1m")->run();
    header("location: /");
}
