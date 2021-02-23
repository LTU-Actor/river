<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);

    if (!empty($_GET["toggle-auto-mode"])){
        $data['auto']['enabled'] = true;
    }
    else{
        $data['auto']['enabled'] = false;
    }


    $data['auto']['level'] = $_GET["show-debug-level"];
    $data['auto']['duration'] = $_GET["show-duration"];
    $data['auto']['timeout'] = $_GET["show-timeout"];


    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);


    $output = exec('. /home/ubuntu/catkin_ws/src/river/src/start.sh 2>&1');
    #$output = shell_exec('ip link')

    echo "<pre>$output</pre>";

    #header('Location: http://192.168.1.38/');
?>