<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $fileTemp = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json";

    $strDataTemp = file_get_contents($fileTemp);
    fclose($fileTemp);
    $data = json_decode($strDataTemp, true);

    if (!empty($_GET['show-text'])){
        $data["show"]["text"]["msg"] = $_GET['show-text'];
    }

    $data["show"]["status"]["msg"] = $_GET['show-status'];
    $data["show"]["text"]["color"] = $_GET['show-text-color'];
    $data["show"]["status"]["color"] = $_GET['show-status-color'];

    if (!empty($_GET['toggle-heartbeat'])){
        $data["settings"]["heartbeat"]["enabled"] = true;
    }
    else{
        $data["settings"]["heartbeat"]["enabled"] = false;
    }

    if (!empty($_GET['toggle-clear-zero'])){
        $data["show"]["status"]["clear0"] = true;
    }
    else{
        $data["show"]["status"]["clear0"] = false;
    }

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);

    shell_exec('cd /home/ubuntu/catkin_ws/src/river/src/ && sudo ./start.sh 2>&1');

    #if (isset($_SERVER["HTTP_REFERER"])) {
    #    header("Location: " . $_SERVER["HTTP_REFERER"]);
    #}
?>
