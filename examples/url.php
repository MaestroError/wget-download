<?php

// Require wgd class
require './../src/wgd.php';

// Construct with URL only
$file = new maestroerror\wgd("https://raw.githubusercontent.com/MaestroError/wget-download/main/images/maestro.png");

// @todo make getCurrCommand method work without extra build
echo "Wget Command: " . $file->getCurrCommand();
$file->run();
