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
echo "Display Brightness: ".$data["display"]["brightness"]."<br>";

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

echo $data["show"]["text"]["color"][0]." ";
echo $data["show"]["text"]["color"][1]." ";
echo $data["show"]["text"]["color"][2]." ";

$command = escapeshellcmd('/home/ubuntu/catkin_ws/src/river/src/commit.py');
$output = shell_exec($command);
echo $output;

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
</form>
</body>
</html>
