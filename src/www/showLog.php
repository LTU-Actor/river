<?php
    $myfile = fopen("/home/ubuntu/catkin_ws/src/river/src/show.log", "r") or die("Unable to open file!");
    while(!feof($myfile)) {
    echo fgets($myfile) . "<br>";
    }
    fclose($myfile);
?>