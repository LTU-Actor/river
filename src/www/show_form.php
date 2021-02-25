<?php
     $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $fileTemp = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json";
    
    $strDataTemp = file_get_contents($fileTemp);
    fclose($fileTemp);
    $data = json_decode($strDataTemp, true);

    if (!empty($_GET['show-text'])){
        $data["show"]["text"]["msg"] = $_GET['show-text'];
        echo "updated show-text<br>";
    }

    
    $data["show"]["status"]["msg"] = $_GET['show-status'];
    echo "udpated show-status<br>";

    if (!empty($_GET['show-text-color'])){
        $data["show"]["text"]["color"] = $_GET['show-text-color'];
        echo "udpated show-text-color<br>";
    }

    if (!empty($_GET['show-status-color'])){
        $data["show"]["status"]["color"] = $_GET['show-status-color'];
        echo "udpated show-status-color<br>";
    }

    if (!empty($_GET['toggle-heartbeat'])){
        $data["settings"]["heartbeat"]["enabled"] = true;
        echo "toggle-heartbeat true<br>";
    }
    else{
        $data["settings"]["heartbeat"]["enabled"] = false;
        echo "toggle-heartbeat false<br>";
    }

    if (!empty($_GET['heartbeat-color'])){
        $data["settings"]["heartbeat"]["color"] = $_GET['heartbeat-color'];
        echo "udpated heartbeat-color<br>";
    }

    if (!empty($_GET['toggle-color-grade'])){
        $data["show"]["status"]["colorGrade"] = true;
        echo "toggle-heartbeat true<br>";
    }
    else{
        $data["show"]["status"]["colorGrade"] = false;
        echo "toggle-heartbeat false<br>";
    }

    if (!empty($_GET['toggle-clear-zero'])){
        $data["show"]["status"]["clear0"] = true;
        echo "toggle-heartbeat true<br>";
    }
    else{
        $data["show"]["status"]["clear0"] = false;
        echo "toggle-heartbeat false<br>";
    }

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);

    shell_exec('cd /home/ubuntu/catkin_ws/src/river/src/ && sudo ./start.sh 2>&1');

    header('Location: http://192.168.0.96/')
?>
