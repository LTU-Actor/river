<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $fileTemp = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json";
    
    $strDataTemp = file_get_contents($fileTemp);
    fclose($fileTemp);
    $data = json_decode($strDataTemp, true);

    $data["settings"]["ros"]["coreIP"] = $_GET['show-host-IP'];
    $data['settings']['ros']['riverIP'] = $_GET['show-ros-IP'];
    $data['settings']['ros']['port'] = $data['show-port'];

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    fclose($file);

    $data["show"]["text"]["msg"] = "Reset";
    $data["show"]["text"]["color"] = "#FFFFFF";
    $data["show"]["status"]["msg"] = "";

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($fileTemp, "w");
    fclose($fileTemp);
    shell_exec('sudo /sbin/reboot');
?>