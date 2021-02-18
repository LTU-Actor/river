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
  ?>
</head>
<body>


<div class="grid-container">
  <div class="container">
    <h3>Defined Settings:</h3>
    <div class="row">
      <div class="col-50">
        <label for="dWidth">Display Width</label>
      </div>
      <div class="col-50">
        <input type=text id="dWidth" name="show-text" placeholder="<?php echo $data["display"]["width"]; ?>" disabled>
      </div>
    </div>
    <div class="row">
      <div class="col-50">
        <label for="dHeight">Display Height</label>
      </div>
      <div class="col-50">
        <input type=text id="dHeight" name="show-text" placeholder="<?php echo $data["display"]["height"]; ?>" disabled>
      </div>
    </div>
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
        <label for="rosRaspberry">ROS Raspberry PI IP</label>
      </div>
      <div class="col-50">
        <input type=text id="rosRaspberry" name="show-text" placeholder="<?php echo $data["settings"]["ros"]["riverIP"]; ?>" disabled>
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
          <input type=text id="UStatus" name="show-status" placeholder="<?php echo $data['show']['status']['msg']; ?>">
        </div>
        <div class="col-25">
          <input type="color" id="UStatus" name="show-status-color" value="<?php echo $data['show']['status']['color']; ?>">
        </div>
      </div>
      <br>
	  <div class="row">
        <div class="col-75">
          <label for="Theartbeat">Toggle heartbeat</label>
		  <input type=checkbox id="Theartbeat" name="toggle-heartbeat">
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
</div>
</body>
</html>