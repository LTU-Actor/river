<?php
    $output = shell_exec('cd /home/ubuntu/catkin_ws/src/river/src/ && sudo ./update.sh 2>&1');
    echo "$output";
    header('Location: http://192.168.1.38/');
?>