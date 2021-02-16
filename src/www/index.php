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
//shell_exec('sudo /sbin/reboot');

?>

<style>
* {
  box-sizing: border-box;
}
input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}

label {
  padding: 12px 12px 12px 0;
  display: inline-block;
}

input[type=submit] {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  float: right;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.container {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}

.col-25 {
  float: left;
  width: 25%;
  margin-top: 6px;
}

.col-75 {
  float: left;
  width: 75%;
  margin-top: 6px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

@media screen and (max-width: 600px) {
  .col-25, .col-75, input[type=submit] {
    width: 100%;
    margin-top: 0;
  }
}
</style>

<br><br>

<form action="" method="post">
	Show text:
	<input type=text name="show-text" placeholder="<?php echo $data['show']['text']['msg']; ?>">
	<input type=submit name="show-text-submit">
</form>
<br>
<form action="" method="post">
	Show Status:
	<input type=text name="show-status" placeholder="<?php echo $data['show']['status']['msg']; ?>">
	<input type=submit name="show-status-submit">
</form>
<br><br>

<h1>Restart Required:</h1>
<div class="container">
	<form action="/home/ubuntu/catkin_ws/src/river/src/settings.php" method="post">
	<div class="row">
		<div class="col-25">
			<label for="fname">Host IP</label>
		</div>
		<div class="col-75">
			<input type=text id="fname" name="show-host-IP" value="<?php echo $data['settings']['ros']['coreIP']; ?>">
		</div>
	</div>
	<div class="row">
		<div class="col-25">
			<label for="fname">Display Width</label>
		</div>
		<div class="col-75">
			<input type=text id="fname" name="show-width" value="<?php echo $data['display']['width']; ?>">
		</div>
	</div>
	<div class="row">
		<div class="col-25">
			<label for="fname">Display Height</label>
		</div>
		<div class="col-75">
			<input type=text id="fname" name="show-height" value="<?php echo $data['display']['height']; ?>">
		</div>
	</div>
	<div class="row">
		<div class="col-25">
			<label for="fname">Display Brightness</label>
		</div>
		<div class="col-75">
			<input type=text id="fname" name="show-brightness" value="<?php echo $data['display']['brightness']; ?>">
		</div>
	</div>
	<br>
	<div class="row">
		<input type="submit" value="Submit">
	</div>
	</form>
</div>
</form>
</body>
</html>
