<?php
    echo $_SERVER['SERVER_NAME'];
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
        echo "http_origin";
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        echo "option";

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
            echo "request method";
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            echo "request header";
        }

        exit(0);
    }
print "<html>
<head>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'>
    </script>
</head>
<body>
    <form name='callback' id='callback' action='' method=''>
        <input type='text' name='client_id' value='1e1e23ed01f8e465707f' />
        <input type='text' name='client_secret' value='5e9616ce1e2dea917750901b71503d4ea88b81dd' />
        <input type='text' name='code' value='".$_GET['code']."' />
        <input type='text' name='redirect_uri' value='http://localhost:7890/git2directadmin/callback.php' />
        <input type='text' name='state' value='".$_GET['state']."' />
    </form>
    <div id='result'>default</div>
    <script type='text/javascript'>
        $.ajax({
            url: 'https://github.com/login/oauth/access_token',
            crossDomain: true,
            type: 'post',
            data: $('#callback').serialize(),
            success: function (data) {
                $('#result').html(data);
                console.log('success', data);
            },
            error: function (err) {
                $('#result').html(err);
                console.log('error', err);
            }
        });
    </script>
</body>
</html>";
    /*$url = 'http://github.com/login/oauth/access_token';
    $data = array(
                "client_id"=>"1e1e23ed01f8e465707f",
                "client_secret"=>"5e9616ce1e2dea917750901b71503d4ea88b81dd",
                "code"=>$_GET['code'],
                "redirect_uri"=>"http://localhost:7890/git2directadmin/callback.php",
                "state" =>$_GET['state']
            );

    $headers = array(
        'Content-type: application/x-www-form-urlencoded',
        'Access-Control-Allow-Origin: *',
        'Accept: application/vnd.github.v3+json'
    );
    $result = test_post_request($url, $data, $headers);
    echo $result;

    if ($result['status'] == 'ok'){

    // Print headers 
    echo $result['header']; 

    echo '<hr />';

    // print the result of the whole request:
    echo $result['content'];

    }
    else {
        echo 'A error occured: ' . $result['error']; 
    }   
    function test_post_request($url, $data, $headers){
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL, $url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers); 
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    function do_post_request($url, $data, $optional_headers = null)
    {
        $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
                ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }
    function post_request($url, $data, $referer='') {
     
        // Convert the data array into URL Parameters like a=b&foo=bar etc.
        $data = http_build_query($data);
     
        // parse the given URL
        $url = parse_url($url);
     
        if ($url['scheme'] != 'http') { 
            die('Error: Only HTTP request are supported !');
        }
     
        // extract host and path:
        $host = $url['host'];
        $path = $url['path'];
     
        // open a socket connection on port 80 - timeout: 30 sec
        $fp = fsockopen($host, 80, $errno, $errstr, 30);
     
        if ($fp){
     
            // send the request headers:
            fputs($fp, "POST $path HTTP/1.1\r\n");
            fputs($fp, "Host: $host\r\n");
     
            if ($referer != '')
                fputs($fp, "Referer: $referer\r\n");
     
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: ". strlen($data) ."\r\n");
            fputs($fp, "Access-Control-Allow-Origin: *\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $data);
     
            $result = ''; 
            while(!feof($fp)) {
                // receive the results of the request
                $result .= fgets($fp, 128);
            }
        }
        else { 
            return array(
                'status' => 'err', 
                'error' => "$errstr ($errno)"
            );
        }
     
        // close the socket connection:
        fclose($fp);
     
        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);
     
        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';
     
        // return as structured array:
        return array(
            'status' => 'ok',
            'header' => $header,
            'content' => $content
        );
    }*/
?>

