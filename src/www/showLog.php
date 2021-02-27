<?php
    $myfile = fopen("/home/ubuntu/catkin_ws/src/river/src/show.log", "r") or die("Unable to open file!");
    echo (filesize("webdictionary.txt"));
    echo fread($myfile,filesize("webdictionary.txt"));
    fclose($myfile);
?>