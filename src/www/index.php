<html>
<head>
<!-- <meta http-equiv="refresh" content="5"> -->
<title>ROS River</title>
</head>
<body>

<?php
$file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
$strData = file_get_contents($file);
$data = json_decode($strData, true);

echo "Display Width: ".$data["display"]["width"]."<br>";
echo "Display Height: ".$data["display"]["height"]."<br>";
echo "Display Brightness: ".$data["display"]["brightness"]."<br><br>";

echo "ROS host IP: ".$data["settings"]["ros"]["coreIP"].":".$data["settings"]["ros"]["port"]."<br>";
echo "ROS Raspberry PI IP: ".$data["settings"]["ros"]["riverIP"]."<br><br>";


if (isset($_POST['show-text'])){
	if ($data["show"]["text"]["msg"] != $_POST['show-text']){
		$data["show"]["text"]["msg"] = $_POST['show-text'];
		
		$jsonData = json_encode($data);
		if (!file_put_contents($file, $jsonData)){
			echo "Failed";
		}
		
		
		#$handle = fopen($file, "w");
		#if (!fwrite($handle, $jsonData)){
		#	echo "Failed";
		#}
		#fclose($file);
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

if (isset($_POST['show-width'])){
	if ($data['display']['width'] != $_POST['show-width']){
			$data['display']['width'] = $_POST['show-width'];
			$jsonData = json_encode($data,JSON_PRETTY_PRINT);
			$handle = fopen($file, "w");
			if (!fwrite($handle, $jsonData)){
					echo "Failed";
			}
			fclose($file);
	}
}

if (isset($_POST['show-height'])){
	if ($data['display']['height'] != $_POST['show-height']){
			$data['display']['height'] = $_POST['show-height'];
			$jsonData = json_encode($data,JSON_PRETTY_PRINT);
			$handle = fopen($file, "w");
			if (!fwrite($handle, $jsonData)){
					echo "Failed";
			}
			fclose($file);
	}
}

if (isset($_POST['show-brightness'])){
	if ($data['display']['brightness'] != $_POST['show-brightness']){
			$data['display']['brightness'] = $_POST['show-brightness'];
			$jsonData = json_encode($data,JSON_PRETTY_PRINT);
			$handle = fopen($file, "w");
			if (!fwrite($handle, $jsonData)){
					echo "Failed";
			}
			fclose($file);
	}
}
#shell_exec('sudo /sbin/reboot');

?>

<br><br>

<form action="" method="post">
	Show text:
	<input type=text name="show-text" value="<?php echo $data['show']['text']['msg']; ?>">
	<input type=submit name="show-text-submit">
	<br>
	Show Status:
	<input type=text name="show-status" value="<?php echo $data['show']['status']['msg']; ?>">
	<input type=submit name="show-status-submit">
</form>
<br><br><br>
Restart Required:
<form action="" method="post">
	Host IP:
	<input type=text name="show-host-IP" value="<?php echo $data['settings']['ros']['coreIP']; ?>">
    <input type=submit name="show-host-IP-submit">
	<br>
	Display Width:
	<input type=text name="show-width" value="<?php echo $data['display']['width']; ?>">
	<input type=submit name="show-width-submit">
	<br>
	Display Height:
	<input type=text name="show-height" value="<?php echo $data['display']['height']; ?>">
	<input type=submit name="show-height-submit">
	<br>
	Display Brightness:
	<input type=text name="show-brightness" value="<?php echo $data['display']['brightness']; ?>">
	<input type=submit name="show-brightness-submit">
</form>
</body>
</html>
