<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);

    $data['auto']['enabled'] = $_GET["toggle-auto-mode"];
    $data['auto']['level'] = $_GET["show-debug-level"];
    $data['auto']['duration'] = $_GET["show-duration"];
    $data['auto']['timeout'] = $_GET["show-timeout"];


    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);
    header('Location: http://192.168.1.38/')
?>