<?php
    shell_exec('cd /home/ubuntu/catkin_ws/src/river/src/ && sudo ./update.sh && sudo ./start.sh');
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
?>