<?php

// Require wgd class
require './../src/wgd.php';

// Construct with parameters array
$conf = [
    "filename" => "config-array-example.png",
    // @todo check for folder
    "folder" => "YOUR FOLDER",
    // @todo check for right URL
    "url" => "#url"
];
$file = new maestroerror\wgd($conf);

// Run download
$file->run();
