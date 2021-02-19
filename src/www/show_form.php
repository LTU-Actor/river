<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);

    if (!empty($_GET['show-text'])){
        $data["show"]["text"]["msg"] = $_GET['show-text'];
        echo "updated show-text<br>";
    }

    echo "<br><br>".$_GET['show-status']."<br><br>";
    if (!empty($_GET['show-status'])){
        $data["show"]["status"]["msg"] = $_GET['show-status'];
        echo "udpated show-status<br>";
    }

    if (!empty($_GET['show-text-color'])){
        $data["show"]["text"]["color"] = $_GET['show-text-color'];
        echo "udpated show-text-color<br>";
    }

    if (!empty($_GET['show-status-color'])){
        $data["show"]["status"]["color"] = $_GET['show-status-color'];
        echo "udpated show-status-color<br>";
    }

    if (!empty($_GET['toggle-heartbeat'])){
        $data["settings"]["hartbeat"]["enabled"] = true;
        echo "toggle-heartbeat true<br>";
    }
    else{
        $data["settings"]["hartbeat"]["enabled"] = false;
        echo "toggle-heartbeat false<br>";
    }

    if (!empty($_GET['heartbeat-color'])){
        $data["settings"]["hartbeat"]["color"] = $_GET['heartbeat-color'];
        echo "udpated heartbeat-color<br>";
    }

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);
    #header('Location: http://192.168.1.38/')
?>