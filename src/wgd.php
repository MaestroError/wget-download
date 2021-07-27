<?php 
namespace maestroerror;
class wgd {

    // It`s better to require name if your downloads has non-readable default names
    const REQUIRENAME = false;
    // It`s better to require folder if you use .txt downloads
    const REQUIREFOLDER = false;

    // filename must be fullname (path+name+extension) ex: /Home/Downloads/newCoolVideo.mp4
    protected ?string $filename = null;
    protected ?string $folder = null;
    protected ?string $limit = null;
    // continue after interrupt
    protected bool $continue = true;
    // background download
    protected bool $bgDownload = true;
    // download with user agent (like from browser)
    protected bool $allowUserAgent = false;
    protected string $userAgent = "Mozilla/5.0 (Linux x86_64; rv:90.0) Gecko/20100101 Firefox/90.0";
    // download via https
    protected bool $checkCertificate = true;
    // download multiple files with .txt
    protected bool $multiple = false;
    protected string $txtSource;
    // Log file name
    protected string $logFile = "wgetlog.txt";
    protected string $options;

    public string $url;
    public $output;
    public $resultCode;

    public $currCommand;
    public $filePath;

    public function __construct($config = "") {

        if (is_array($config)) {
            if (isset($config['filename'])) {
                $this->filename = $config['filename'];
            } else {
                if ($this::REQUIRENAME) {
                    throw new \Exception('filename is required parameter');
                }
            }
    
            if (isset($config['folder'])) {
                $this->folder = $config['folder'];
            } else {
                if ($this::REQUIREFOLDER) {
                    throw new \Exception('folder is required parameter');
                }
            }
    
            if (isset($config['url'])){
                $this->url = $config['url'];
            } else {
                throw new \Exception('URL is required parameter');
            }
        } elseif (is_string($config) && !$this::REQUIRENAME && !$this::REQUIREFOLDER) {
            $this->url = $config;
        } else {
            throw new \Exception('Unknown error occured while constructing '.get_class($this).". Please, check if you provided URL while constructing and if there is some more required parameters (see REQUIRENAME and REQUIREFOLDER constants)");
        }
        
        return $this;
    }

    
    /** Set options */

    public function name($name) {
        $this->filename = $name;
        return $this;
    }
    
    public function folder($folder) {
        $this->folder = $folder;
        return $this;
    }

    public function speedLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function continueIfStopped() {
        $this->continue = true;
        return $this;
    }

    public function userAgent($userAgent) {
        if ($this->allowUserAgent) {
            $this->userAgent = $userAgent;
        }
        return $this;
    }

    public function allowUserAgent() {
        $this->allowUserAgent = true;
        return $this;
    }

    public function secure() {
        $this->checkCertificate = true;
        return $this;
    }

    public function silent() {
        $this->bgDownload = true;
        return $this;
    }

    public function wait() {
        $this->bgDownload = false;
        return $this;
    }

    public function multiple() {
        $this->multiple = true;
        return $this;
    }

    public function file($txt) {
        if ($this->multiple) {
            $this->txtSource = $txt;
        }
        return $this;
    }

    public function url($url) {
        $this->url = $url;
        return $this;
    }

    public function setLog($filename) {
        $this->logFile = $filename;
        return $this;
    }


    /** Retrive info */

    public function getUserAgent() {
        if ($this->allowUserAgent) {
            if ($this->userAgent) {
                return $this->userAgent;
            } else {
                throw new \Exception('User agent allowed but not set, please provide some');
            }
        } else {
            throw new \Exception('getUserAgent() method`s \Exception: user agent isn`t enabled and isn`t set. please, enable it with method allowUserAgent()');
        }
    }

    public function getCurrLogFile() {
        if ($this->logFile) {
            return $this->logFile;
        } else {
            return false;
        }
    }

    public function getCurrCommand() {
        if ($this->currCommand) {
            return $this->currCommand;
        } else {
            return false;
        }
    }

    function getLogLastLines() {
        $file = file($this->logFile);
        $line = count($file)-1;
        $lastLines = $file[$line-2] ." | ". $file[$line-1] ." | ". $file[$line];
        $comment = $lastLines;
        return $comment;
    }


    /** Execution Process */

    public static function chefo($path) {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        } else {
            return true;
        }
    }

    protected function setOptions() {
        $this->options = "";

        $filename = $this->filename;
        $folder = $this->folder;
        $limit = $this->limit;

        if ($this->filename) {
            $this->options .= ' -O "'.$filename.'"';
        }
        if ($this->folder) {
            $this->options .= " -P $folder";
        }
        if ($this->limit) {
            $this->options .= ' --limit-rate="'.$limit.'"';
        }
        if ($this->continue) {
            $this->options .= " -c";
        }
        if ($this->bgDownload) {
            $this->options .= " -b";
            $log = $this->logFile;
            $this->options .= " --append-output=$log";
        }
        if (!$this->checkCertificate) {
            $this->options .= " --no-check-certificate";
        }
        if ($this->allowUserAgent) {
            $ua = $this->getUserAgent();
            $this->options .= ' --user-agent="'.$ua.'"';
        }
    }

    private function executeAsync($command = null){
        // background execution
        if(!$command){
            throw new \Exception("No command given");
        }
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $command, "r")); 
        }
        else {
            exec($command . " > /dev/null &");  
        }
     }

    public function build() {
        $this->setOptions();
        $options = $this->options;
        if ($this->multiple && $this->txtSource) {
            
            if (!$this->folder && !$this::REQUIREFOLDER) {
                trigger_error("while downloading multiple files with .txt, it`s better practice to set folder, to avoid this error require folder with maestroerror\wgd::REQUIREFOLDER constant", E_USER_WARNING);
            }
            
            if (file_exists($this->txtSource)) {
                $txt = realpath($this->txtSource);
                $command = "wget $options -i $txt";
            }
        } else {
            $url = $this->url;
            $command = "wget $options $url";
        }
        $this->currCommand = $command;
        $this->filePath = $this->filename; // ???
    }

    public function run() {
        $this->build();
        $command = $this->currCommand;
        if ($this->bgDownload) {
            $this->executeAsync($command);
        } else {
            exec($command, $output, $resultCode);
            $this->output = $output;
            $this->resultCode = $resultCode;
        }
        return $this;
    }

}
