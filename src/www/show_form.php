<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);

    echo "test";

    if (!empty(isset($_GET['show-text']))){
        $data["show"]["text"]["msg"] = $_GET['show-text'];
        echo "updated show-text";
    }

    if (!empty($_GET['show-status'])){
        $data["show"]["status"]["msg"] = $_GET['show-status'];
        echo "udpated show-status";
    }

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);
    header('Location: http://192.168.1.38/')
?>