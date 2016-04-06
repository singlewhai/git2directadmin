<?php
    require_once('config.php');
    require_once('lib/FtpConnect.php');

    $ftpConnect = new FtpConnect(FTP_HOSTNAME, FTP_PORT, FTP_TIMEOUT, FTP_USERNAME, FTP_PASSWORD, FTP_DIRECTORY);
    echo $ftpConnect->connect().'<br>';

    $date = new DateTime();
    $dir = FTP_DEPLOY_DIR.'/'.$date->getTimestamp();
    echo $dir.'<br>';

    // create deploy directory
    $ftpConnect->createDirectory(FTP_DEPLOY_DIR);

    // dump
    //var_dump($ftpConnect->getFileList());
    echo '<br>';

    // change current directory to deploy directory
    $ftpConnect->setDirectory(FTP_DEPLOY_DIR);

    // create directory
    echo $ftpConnect->createDirectory($dir);
    echo '<br>';
    
    // dump
    //var_dump($ftpConnect->getFileList());
    echo '<br>';

    // delete empty directory
    echo $ftpConnect->delete($dir);
    echo '<br>';

    // dump
    //var_dump($ftpConnect->getFileList());
    echo '<br>';

    //var_dump($ftpConnect->uploadDirectory("/home/whaikung/code/project/public/git2directadmin",$dir));

    $ftpConnect->disconnect();

    
?>

