<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);

    echo "t";
    echo $_POST['show-host-IP'];
    echo $_GET['show-host-IP'];
    if (isset($_GET['show-host-IP'])){
        $data["settings"]["ros"]["coreIP"] = $_GET['show-host-IP'];
        echo "updated show-host-IP";
    }
    echo $_GET['show-width'];
    if (isset($_GET['show-width'])){
        $data['display']['width'] = $_GET['show-width'];
        echo "udpated show-width";
    }

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);
    #header('Location: http://192.168.1.38/')
?>