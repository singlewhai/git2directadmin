<?php
    require_once('config.php');
    require_once('lib/FtpConnect.php');

    echo FTP_DIRECTORY;
    $ftpConnect = new FtpConnect(FTP_HOSTNAME, FTP_PORT, FTP_TIMEOUT, FTP_USERNAME, FTP_PASSWORD, FTP_DIRECTORY);
    echo $ftpConnect->connect();
    var_dump($ftpConnect->getFileList());
    $ftpConnect->disconnect();
?>
