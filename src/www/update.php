<?php
    shell_exec('cd /home/ubuntu/catkin_ws/src/river/src/ && sudo bash ./update.sh && sudo bash ./start.sh');
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
?>