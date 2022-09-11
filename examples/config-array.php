<?php

// Require wgd class
require './../src/wgd.php';

// Construct with parameters array
$conf = [
    "filename" => "config-array-example.png",
    // @todo check for folder and create if not exists
    "folder" => "YOUR FOLDER",
    // @todo check for right URL
    "url" => "https://raw.githubusercontent.com/MaestroError/wget-download/main/images/maestro.png"
];
$file = new maestroerror\wgd($conf);

// Run download
$file->run();
