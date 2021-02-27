Number of logs:
<?php
    $linecount = 0;
    $myfile = fopen("/home/ubuntu/catkin_ws/src/river/src/main.log", "r") or die("Unable to open file!");
    while(!feof($myfile)) {
        $line = fgets($myfile);
        $linecount++;
    }
    fclose($myfile);
    echo $linecount - 1;
?>

<br><br>
Logs:
<br>

<?php
    $myfile = fopen("/home/ubuntu/catkin_ws/src/river/src/main.log", "r") or die("Unable to open file!");
    while(!feof($myfile)) {
    echo fgets($myfile) . "<br>";
    }
    fclose($myfile);
?>