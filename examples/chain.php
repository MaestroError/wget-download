<?php 

// Require wgd class
require './../src/wgd.php';

// Construct without parameters
$file = new maestroerror\wgd();
$file->folder("YOUR FOLDER")->name("chain.png")
        ->url("https://raw.githubusercontent.com/MaestroError/wget-download/main/images/maestro.png");

// Run download
$file->run();