<?php

// Require wgd class
require './../src/wgd.php';

// Construct with URL only
$file = new maestroerror\wgd("#url");

// @todo make getCurrCommand method work without extra build
echo "Wget Command: " . $file->getCurrCommand();
$file->run();
