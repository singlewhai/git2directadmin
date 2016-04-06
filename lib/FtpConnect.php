<?php
    // FtpConnect - this is a class to connect ftp

    class FtpConnect {

        private $hostname;
        private $port;
        private $timeout;
        private $username;
        private $password;
        private $directory;
        private $ftp_conn;
        private $blackList = array('.', '..', 'Thumbs.db', '.git', '.gitignore', 'README.md');

        public function JsonData($message, $statusCode){
            return json_encode(array('message' => $message, 'statusCode' => $statusCode));
        }

        public function __construct($hostname, $port, $timeout, $username, $password, $directory) {
            $this->hostname = $hostname;
            $this->port     = $port;
            $this->timeout  = $timeout;
            $this->username = $username;
            $this->password = $password;
            $this->directory= $directory;
        }

        public function __destruct() {
            $this->disconnect();
        }

        public function connect(){
            $this->ftp_conn = ftp_connect($this->hostname, $this->port, $this->timeout) or die("Could not connect to $this->hostname");
            if (@ftp_login($this->ftp_conn, $this->username, $this->password))
            {
                return $this->JsonData("Connection established.", 200);
            }
            else
            {
                return $this->JsonData("Couldn't establish a connection.", 404);
            }
        }

        public function disconnect(){
            if (isset($this->ftp_conn)) {
                ftp_close($this->ftp_conn);
                unset($this->ftp_conn);
            }        
        }

        public function setDirectory($path){
            $this->directory = $path;
        }

        // get file list of current directory
        public function getFileList(){
            return ftp_nlist($this->ftp_conn, $this->directory);     
        }

        // create derectory
        public function createDirectory($directory){
            if (ftp_mkdir($this->ftp_conn, $directory))
            {
                return $this->JsonData("Successfully created $directory", 200);
            }
            else
            {
                return $this->JsonData("Error while creating $directory", 404);
            }
        }
        
        // delete derectory
        public function delete($path){
            if(@ftp_rmdir($this->ftp_conn, $path) | @ftp_delete($this->ftp_conn, $path)){
                return $this->JsonData("Directory $path was deleted", 200);
            }else{
                $files = ftp_nlist($this->ftp_conn, $path);
                foreach ($files as $file)
                {
                    $this->delete($path.'/'.$file);
                }
                return $this->delete($path);
            }
        }

        public function uploadDirectory($localPath, $remotePath){
            return $this->recurse_directory($localPath, $localPath, $remotePath);
        }

        private function recurse_directory($rootPath, $localPath, $remotePath) {
            $errorList = array();
            if (!is_dir($localPath)) throw new Exception("Invalid directory: $localPath");
            chdir($localPath);
            $directory = opendir(".");
            while ($file = readdir($directory)) {
                if (in_array($file, $this->blackList)) continue;
                if (is_dir($file)) {
                    $errorList["$remotePath/$file"] = $this->createDirectory("$remotePath/$file");
                    $errorList[] = $this->recurse_directory($rootPath, "$localPath/$file", "$remotePath/$file");
                    chdir($localPath);
                } else {
                    $errorList["$remotePath/$file"] = $this->uploadFile("$localPath/$file", "$remotePath/$file");
                }
            }
            return $errorList;
        }

        public function uploadFile($localPath, $remotePath) {
            $error = "";
            try {
                ftp_put($this->ftp_conn, $remotePath, $localPath, FTP_BINARY); 
            } catch (Exception $e) {
                if ($e->getCode() == 2) $error = $e->getMessage(); 
            }
            return $error;
        }
    }
?>
