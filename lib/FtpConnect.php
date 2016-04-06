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

        function JsonData($message, $statusCode){
            return json_encode(array('message' => $message, 'statusCode' => $statusCode));
        }

        function __construct($hostname, $port, $timeout, $username, $password, $directory) {
            $this->hostname = $hostname;
            $this->port     = $port;
            $this->timeout  = $timeout;
            $this->username = $username;
            $this->password = $password;
            $this->directory= $directory;
            $this->ftp_conn = null;
        }
        
        function connect(){
            $this->ftp_conn = ftp_connect($this->hostname, $this->port, $this->timeout) or die("Could not connect to $ftp_server");
            if (@ftp_login($this->ftp_conn, $this->username, $this->password))
            {
                return $this->JsonData("Connection established.", 200);
            }
            else
            {
                return $this->JsonData("Couldn't establish a connection.", 404);
            }
        }

        function disconnect(){
            ftp_close($this->ftp_conn);
        }

        // get file list of current directory
        function getFileList(){
            return ftp_nlist($this->ftp_conn, $this->directory);     
        }

        // try to delete file
        function deleteFile($filename){
            if (ftp_delete($this->ftp_conn, $filename))
            {
                return JsonData("$file deleted", 200);
            }
            else
            {
                return JsonData("Could not delete $file", 404);
            }
        }

    }
?>
