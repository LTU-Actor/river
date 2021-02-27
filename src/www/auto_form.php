<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $fileTemp = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json";

    $strDataTemp = file_get_contents($fileTemp);
    fclose($fileTemp);
    $data = json_decode($strDataTemp, true);

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
    fclose($file);

    shell_exec('cd /home/ubuntu/catkin_ws/src/river/src/ && sudo ./start.sh 2>&1');

    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
?>