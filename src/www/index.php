<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <script>

  </script>
  <?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/data.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);
  ?>
</head>
<body>
<div class="grid-container">
  <div class="container">
    <h3>Defined Settings:</h3>
	  <div class="row">
      <div class="col-50">
        <label for="rosHostIP">ROS host IP</label>
      </div>
      <div class="col-50">
        <input type=text id="rosHostIP" name="show-text" placeholder="<?php echo $data["settings"]["ros"]["coreIP"]; ?>" disabled>
      </div>
    </div>
	  <div class="row">
      <div class="col-50">
        <label for="rosPort">ROS port</label>
      </div>
      <div class="col-50">
        <input type=text id="rosPort" name="show-text" placeholder="<?php echo $data["settings"]["ros"]["port"]; ?>" disabled>
      </div>
    </div>
    <div class="row">
      <div class="col-50">
        <label for="rosRaspberry">ROS Raspberry PI IP</label>
      </div>
      <div class="col-50">
        <input type=text id="rosRaspberry" name="show-text" placeholder="<?php echo $data["settings"]["ros"]["riverIP"]; ?>" disabled>
	    </div>
	  </div>
    <div class="row">
      <div class="col-50">
        <label for="dWidth">Display width</label>
      </div>
      <div class="col-50">
        <input type=text id="dWidth" name="show-text" placeholder="<?php echo $data["display"]["width"]; ?>" disabled>
      </div>
    </div>
    <div class="row">
      <div class="col-50">
        <label for="dHeight">Display height</label>
      </div>
      <div class="col-50">
        <input type=text id="dHeight" name="show-text" placeholder="<?php echo $data["display"]["height"]; ?>" disabled>
      </div>
    </div>
	  <div class="row">
      <div class="col-50">
        <label for="dBrightness">Display brightness</label>
      </div>
      <div class="col-50">
        <input type=text id="dBrightness" name="show-text" placeholder="<?php echo $data["display"]["brightness"]; ?>" disabled>
      </div>
    </div>
  </div>

  <div class="container">
    <h3>Show:</h3>
    <form action="show_form.php">
      <div class="row">
        <div class="col-25">
          <label for="UText">Update text</label>
        </div>
        <div class="col-50">
          <input type=text id="UText" name="show-text" placeholder="<?php echo $data['show']['text']['msg']; ?>">
        </div>
        <div class="col-25">
          <input type="color" id="UText" name="show-text-color" value="<?php echo $data['show']['text']['color']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label for="UStatus">Update status</label>
        </div>
        <div class="col-50">
          <input type=text id="UStatus" name="show-status" value="<?php echo $data['show']['status']['msg']; ?>">
        </div>
        <div class="col-25">
          <input type="color" id="UStatus" name="show-status-color" value="<?php echo $data['show']['status']['color']; ?>">
        </div>
      </div>
	    <div class="row">
        <div class="col-75">
          <label for="Theartbeat">Toggle heartbeat</label>
		      <input type=checkbox id="Theartbeat" name="toggle-heartbeat" value="true" <?php if ($data['settings']['hartbeat']['enabled']){echo checked;} ?>>
        </div>
		    <div class="col-25">
          <input type="color" id="Theartbeat" name="heartbeat-color" value="<?php echo $data['settings']['hartbeat']['color']; ?>">
        </div>
      </div>
      <br>

      <div class="row">
        <input type="submit" value="Submit">
      </div>
    </form>
  </div>

  <div class="container">
    <h3>Automatic mode:</h3>
    <form action="auto_form.php">
      <div class="row">
        <div class="col-75">
          <label for="TAutoMode">Toggle automatic mode</label>
		      <input type=checkbox id="TAutoMode" name="toggle-auto-mode" value="true" <?php if ($data['auto']['enabled']){echo checked;} ?>>
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label for="DLevel">Debug Level</label>
        </div>
        <div class="col-75">
          <input type=text id="DLevel" name="show-debug-level" value="<?php echo $data['auto']['level']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label for="Duration">Duration</label>
        </div>
        <div class="col-75">
          <input type=text id="Duration" name="show-duration" value="<?php echo $data['auto']['duration']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label for="Timeout">Timeout</label>
        </div>
        <div class="col-75">
          <input type=text id="Timeout" name="show-timeout" value="<?php echo $data['auto']['timeout']; ?>">
        </div>
      </div>

      <br>
      <div class="row">
        <input type="submit" value="Submit">
      </div>
    </form>
  </div>

  <div class="container">
    <h3>Settings (Restart Required):</h3>
    <form action="restart_form.php">
    <div class="row">
      <div class="col-25">
        <label for="HostIP">Host IP</label>
      </div>
      <div class="col-75">
        <input type=text id="HostIP" name="show-host-IP" value="<?php echo $data['settings']['ros']['coreIP']; ?>">
      </div>
    </div>
	  <div class="row">
      <div class="col-25">
        <label for="Port">ROS port</label>
      </div>
      <div class="col-75">
        <input type=text id="Port" name="show-post" value="<?php echo $data['settings']['ros']['port']; ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="DWidth">Display Width</label>
      </div>
      <div class="col-75">
        <input type=text id="fname" name="show-width" value="<?php echo $data['display']['width']; ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="DHeight">Display Height</label>
      </div>
      <div class="col-75">
        <input type=text id="fname" name="show-height" value="<?php echo $data['display']['height']; ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="DBrightness">Display Brightness</label>
      </div>
      <div class="col-75">
        <input type=text id="DBrightness" name="show-brightness" value="<?php echo $data['display']['brightness']; ?>">
      </div>
    </div>
    <br>
    <div class="row">
      <input type="submit" value="Submit and Reboot">
    </div>
    </form>
  </div>
</div>
</body>
</html>