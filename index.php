<?php
    require_once('config.php');
    require_once('lib/FtpConnect.php');

    
    $ftpConnect = new FtpConnect(FTP_HOSTNAME, FTP_PORT, FTP_TIMEOUT, FTP_USERNAME, FTP_PASSWORD, FTP_DIRECTORY);
    echo $ftpConnect->connect().'<br>';

    $date = new DateTime();
    $dir = FTP_DEPLOY_DIR.'/'.$date->getTimestamp();
    echo $dir.'<br>';

    // create deploy directory
    echo $ftpConnect->createDirectory(FTP_DEPLOY_DIR);
    echo '<br>';

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
    var_dump($ftpConnect->getFileList());
    echo '<br>';

    //var_dump($ftpConnect->uploadDirectory("/home/whaikung/code/project/public/git2directadmin",$dir));

    $ftpConnect->disconnect();

?>
<html>
<head></head>
<body>
    <input type="button" value="Authentication" onclick="github_auth()"/>
        <script type="text/javascript">
            function github_auth(){
                window.location.href = "https://github.com/login/oauth/authorize?client_id=1e1e23ed01f8e465707f&redirect_uri=http://localhost:7890/git2directadmin/callback.php&scope=repo,user&state=123456789";
            }
        </script>

</body>
</html>
