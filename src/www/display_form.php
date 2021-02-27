<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $fileTemp = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json";

    $strDataTemp = file_get_contents($fileTemp);
    fclose($fileTemp);
    $data = json_decode($strDataTemp, true);

    $data['display']['width'] = $_GET['show-width'];
    $data['display']['height'] = $_GET['show-height'];
    $data['display']['brightness'] = $_GET['show-brightness'];

    $jsonData = json_encode($data,JSON_PRETTY_PRINT);
    $handle = fopen($file, "w");
    if (!fwrite($handle, $jsonData)){
        echo "Failed";
    }
    fclose($file);

    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
?>