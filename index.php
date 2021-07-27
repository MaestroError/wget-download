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

// Construct with parameteres array
$conf = [
    "filename" => "NewFileName.mp4",
    "folder" => "YOUR FOLDER",
    "url" => "YOUR URL"
];
$file = new maestroerror\wgd($conf);

// Construct with URL only
$url = "YOUR URL";
$file = new maestroerror\wgd($url);

// Construct without parameters
$file = new maestroerror\wgd();
$file->folder("YOUR FOLDER")->name("/Home/Downloads/newFileName.mp4")->url("YOUR URL");