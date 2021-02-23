<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);
    
    $fileTemp = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json";
    $strDataTemp = file_get_contents($fileTemp);
    $dataTemp = json_decode($strDataTemp, true);

    $data = $dataTemp

    fclose($fileTemp);

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

    shell_exec('cd /home/ubuntu/catkin_ws/src/river/src/ && sudo ./start.sh 2>&1');

    header('Location: http://192.168.1.38/');
?>