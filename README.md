# wget-download
easy to use, chainable PHP class for downloading files, uses wget CLI

# Docs
I will try to provide some instructions, to better understand and use the main features of this little library.   

- [Installation](#installation)
    - [Composer](#via-composer)
    - [From github](#from-github)
- [Initialization](#initialization-of-class-construction)
- [Options](#Options)
- [Logs](#logs)
- [Run](#run)

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

## Initialization of class (Construction)
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
Sometimes you should use a user agent for download. first, you need to allow and after set user agent for your download:
```
$file->allowUserAgent()->userAgent($user_agent_string)
```
for multiple .txt downloads, you need .txt file with urls (line by line). Like in user agent case, first, allow multiple download with *multiple* method and then give .txt file to run:
```
$file->multiple()->file($filepath);
```
## Logs
default log file is defined as protected property in wgd class `protected string $logFile = "wgetlog.txt";`, but if you need, you can specify with *setLog* method:
```
$file->setLog("/Home/Downloads/newFileLog.txt");
$file->setLog("newFileLog.txt");
```
## Run
The last step is an execution, you can start your download with *run* method: `$file->run()`.    
Now let's say, we need to download file on background, with secure connection, set speed limit 1MB and allow continue:
```
$conf = [
    "filename" => "/Home/Downloads/newFileName.mp4",
    "folder" => "YOUR FOLDER",
    "url" => "YOUR URL"
];
$file = new maestroerror\wgd($conf);
$file->setLog("newFileLog.txt")->silent()->secure()->speedLimit("1m")->continueIfStopped()->run()
```

---------------------------------      
### To Do
- Search for todo comments and fix them
- Add more use cases in examples
- Add config files
- Update documentation
- New release