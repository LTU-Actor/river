
<?php 
    $linecount = 0;
    $myfile = fopen("/home/ubuntu/catkin_ws/src/river/src/show.log", "r");
    while(!feof($myfile)) {
        $linecount++;
    }
    fclose($myfile);
    echo $linecount;
?>