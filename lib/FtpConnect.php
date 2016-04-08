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
            if (@ftp_mkdir($this->ftp_conn, $directory))
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

        /**
         * varsion is a name of folder in deploy directory
         * name is a name of new folder will be under the public_html
         */
        public function deploy($version, $name){
              
        }

        /** 
         * this private function use for copying to file on server
         */
        private function copyServerFile($src, $des){
            /* 
            if(ftp_chdir($this->conn, $src)){
                $rawList = ftp_nlist($conn, "."); 
                if (!in_array($destDir, $rawList)) 
                { 
                    ftp_mkdir($conn, $destDir); 
                    //@ftp_chmod($conn, 0777, $image_dir); 
                }
            }
           
            $files = ftp_nlist($this->ftp_conn, $path);
                foreach ($files as $file)
                {
                    $this->delete($path.'/'.$file);
                }
                return $this->delete($path);

            $srcDir = $this->fieldarray['sourceItemId']; 
            $destDir = $this->fieldarray['itemId']; 
            $localDir = PUBLIC_PATH . 'photos/' . $this->fieldarray['imageType'] . '/original/' . $this->fieldarray['itemId']; 
             
            if ($conn = ftp_connect(PHOTO_SERVER)) 
            { 
                if (ftp_login($conn, PHOTO_SERVER_USER, PHOTO_SERVER_PW)) 
                { 
                    ftp_pasv($conn, true); 
                    if (ftp_chdir($conn, PHOTO_SERVER_UPLOAD_DIR)) 
                    { 
                        $rawList = ftp_nlist($conn, "."); 
                        if (!in_array($destDir, $rawList)) 
                        { 
                            ftp_mkdir($conn, $destDir); 
                            //@ftp_chmod($conn, 0777, $image_dir); 
                        } 
                         
                        $files = ftp_nlist($conn, $srcDir); 
                        foreach ($files as $file) 
                        { 
                            if ($file != "." && $file != "..") 
                            { 
                                $srcFile = PHOTO_SERVER_FTP_GET_SRC_DIR . '/' . $srcDir . '/' . $file; 
                                $localFile = $localDir . '/' . $file; 
                                 
                                if (ftp_get($conn, $localFile, $srcFile, FTP_BINARY)) 
                                { 
                                    $upload = ftp_put($conn, $destDir . "/" . $file, $localFile, FTP_BINARY); 
                                } 
                            } 
                        } 
                    } 
                }
            }*/
        }
    }
?>
