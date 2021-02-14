<html>
<head>
<!-- <meta http-equiv="refresh" content="5"> -->
<title>ROS River</title>
</head>
<body>

<?php
$file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
$strData = file_get_contents("/home/ubuntu/catkin_ws/src/river/src/data.json");
$data = json_decode($strData, true);

echo "Display Width: ".$data["display"]["width"]."<br>";
echo "Display Height: ".$data["display"]["height"]."<br>";
echo "Display Brightness: ".$data["display"]["brightness"]."<br><br>";

echo "ROS host IP: ".$data["settings"]["ros"]["coreIP"].":".$data["settings"]["ros"]["port"]."<br>";
echo "ROS Raspberry PI IP: ".$data["settings"]["ros"]["riverIP"]."<br><br>";


if (isset($_POST['show-text'])){
	if ($data["show"]["text"]["msg"] != $_POST['show-text']){
		$data["show"]["text"]["msg"] = $_POST['show-text'];
		$jsonData = json_encode($data,JSON_PRETTY_PRINT);
		$handle = fopen($file, "w");
		if (!fwrite($handle, $jsonData)){
			echo "Failed";
		}
		fclose($file);
	}
}

if (isset($_POST['show-status'])){
        if ($data["show"]["status"]["msg"] != $_POST['show-status']){
                $data["show"]["status"]["msg"] = $_POST['show-status'];
                $jsonData = json_encode($data,JSON_PRETTY_PRINT);
                $handle = fopen($file, "w");
                if (!fwrite($handle, $jsonData)){
                        echo "Failed";
                }
                fclose($file);
        }
}

if (isset($_POST['show-host-IP'])){
        if ($data["settings"]["ros"]["coreIP"] != $_POST['show-host-IP']){
                $data["settings"]["ros"]["coreIP"] = $_POST['show-host-IP'];
                $jsonData = json_encode($data,JSON_PRETTY_PRINT);
                $handle = fopen($file, "w");
                if (!fwrite($handle, $jsonData)){
                        echo "Failed";
                }
                fclose($file);
        }
}

if (count($_POST) > 0 && isset($_POST['show-reboot-submit'])){
	$output = shell_exec('sudo /sbin/reboot 2>&1');
	#$output = shell_exec("ls 2>&1");
	echo $output;
}

?>

<br><br>

<form action="" method="post">
	Show text:
	<input type=text name="show-text" value="<?php echo $data['show']['text']['msg']; ?>">
	<input type=submit name="show-text-submit">
	<br>
	<br>
	Show Status:
	<input type=text name="show-status" value="<?php echo $data['show']['status']['msg']; ?>">
	<input type=submit name="show-status-submit">
	<br>
	<br>
	Host IP:
	<input type=text name="show-host-IP" value="<?php echo $data['settings']['ros']['coreIP']; ?>">
    <input type=submit name="show-host-IP-submit">
	<br>
	<br>
	Reboot and update:
	<input type=submit name="show-reboot-submit">
</form>
</body>
</html>
