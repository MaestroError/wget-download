<?php 
namespace maestroerror;
class wgd {

    const REQUIRENAME = false;
    const REQUIREFOLDER = false;

    protected ?string $filename = null;
    protected ?string $folder = null;
    protected ?string $limit = null;
    // continue after interrupt
    protected bool $continue = true;
    // background download
    protected bool $bgDownload = true;
    // download with user agent (like from browser)
    protected bool $allowUserAgent = false;
    protected string $userAgent;
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

    public function __construct(array $config) {

        if (isset($config['filename'])) {
            $this->filename = $config['filename'];
        } else {
            if ($this::REQUIRENAME) {
                throw new Exception('filename is required parameter');
            }
        }

        if (isset($config['folder'])) {
            $this->folder = $config['folder'];
        } else {
            if ($this::REQUIREFOLDER) {
                throw new Exception('folder is required parameter');
            }
        }

        if (isset($config['url'])){
            $this->url = $config['url'];
        } else {
            throw new Exception('URL is required parameter');
        }
        return $this;
    }

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
        if ($this->allowUserAgent) {
            $this->txtSource = $txt;
        }
        return $this;
    }

    public function url($url) {
        $this->url = $url;
        return $this;
    }

    public function getUserAgent() {
        if ($this->userAgent) {
            return $this->userAgent;
        } else {
            throw new Exception('User agent allowed but not set, please provide some');
        }
    }

    protected function setOptions() {
        $this->options = "";

        $filename = $this->filename;
        $folder = $this->folder;
        $limit = $this->limit;

        if ($this->filename) {
            $this->options .= " -O $filename";
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
            throw new Exception("No command given");
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
            if (file_exists($this->txtSource)) {
                $txt = realpath($this->txtSource);
                $command = "wget $options -i $txt";
            }
        } else {
            $url = $this->url;
            $command = "wget $options $url";
        }
        $this->currCommand = $command;
        $this->filePath = $this->folder.DIRECTORY_SEPARATOR.$this->filename;
        return $this;
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
