<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);

    $data["settings"]["ros"]["coreIP"] = $_GET['show-host-IP'];
    $data['display']['width'] = $_GET['show-width'];
    $data['display']['height'] = $_GET['show-height'];
    $data['display']['brightness'] = $_GET['show-brightness'];

    $data["show"]["text"]["msg"] = "Reset";
    $data["show"]["text"]["color"] = "#FFFFFF";
    $data["show"]["status"]["msg"] = "";

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);
    #shell_exec('sudo /sbin/reboot');
?>