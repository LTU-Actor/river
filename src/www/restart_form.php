<?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);

    
    echo $_POST['show-host-IP']."<br>";
    echo $_GET['show-width']."<br>";
    echo $_GET['show-height']."<br>";
    echo $_GET['show-brightness'];

    #header('Location: http://192.168.1.38/')
?>