<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <script>

  </script>
  <?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);

    echo "Display Width: ".$data["display"]["width"]."<br>";
    echo "Display Height: ".$data["display"]["height"]."<br>";
    echo "Display Brightness: ".$data["display"]["brightness"]."<br><br>";

    echo "ROS host IP: ".$data["settings"]["ros"]["coreIP"].":".$data["settings"]["ros"]["port"]."<br>";
    echo "ROS Raspberry PI IP: ".$data["settings"]["ros"]["riverIP"]."<br><br>";
  ?>
</head>
<body>
<div class="container">

</div>
<div class="container">
  <h2>Settings:</h2>(Restart Required)
	<form action="restart_form.php">
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
</body>
</html>