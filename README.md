# wget-download
easy to use, chainable PHP class for downloading files, uses wget CLI

# Instructions
I will try to provide some instructions, to better understand and use the main features of this little library.   

## installation
#### via Composer:  
```
composer require maestroerror/wget-download  
```
*Don't forget to require autoload.php file*   
#### from github:
```
git clone https://github.com/MaestroError/wget-download.git
```

## Initialisation of class (Construction)
You can construct object with 3 different ways: with array of configs, with string of URL or without any parameters (and provide them further). Which you choose, it depends on your needs:
```
// Construct with parameteres array
$conf = [
    "filename" => "/Home/Downloads/newFileName.mp4",
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
```
## Options
##### Some main of options you can define with just one method
set speed limit:
```
$file->speedLimit("500k");
```
enable continue after the interrupt, so if the internet connection stopped it will automatically resume the download after the connection is restored:
```
$file->continueIfStopped();
```
**$checkCertificate** is defined in wgd class as protected boolean property and by default it is set to *false*, you can use *secure* method, to allow **HTTPS** certificate check for specific downloads:
```
$file->secure();
```
By default silent (download in background) mode is enabled, but for some specific downloads you can specify conditions:
```
$file->silent();
$file->wait(); // Waits before downloaded
```
