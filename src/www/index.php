<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <script src="formValidate.js"></script>
  <?php
    $file = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json";
    $strData = file_get_contents($file);
    $data = json_decode($strData, true);
  ?>
</head>
<body>
  <div class="grid-container">
    <div class="container">
      <h3>Show:</h3>
      <form name="show_form" action="show_form.php" onsubmit="return validateShowForm()">
        <div class="row">
          <div class="col-25">
            <label>Update text</label>
          </div>
          <div class="col-50">
            <input type=text name="show-text" placeholder="<?php echo $data['show']['text']['msg']; ?>">
          </div>
          <div class="col-25">
            <input type="color"name="show-text-color" value="<?php echo $data['show']['text']['color']; ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Update status</label>
          </div>
          <div class="col-50">
            <input type=text name="show-status" value="<?php echo $data['show']['status']['msg']; ?>">
          </div>
          <div class="col-25">
            <input type="color" name="show-status-color" value="<?php echo $data['show']['status']['color']; ?>" <?php if ($data['show']['status']['colorGrade']){echo disabled;} ?>>
          </div>
        </div>
        <div class="row">
          <label>Toggle heartbeat</label>
          <input type=checkbox name="toggle-heartbeat" value="true" <?php if ($data['settings']['hartbeat']['enabled']){echo checked;} ?>>
        </div>
        <div class="row">
          <div class="col-75">
            <label>Toggle clear zero</label>
            <input type=checkbox name="toggle-clear-zero" value="true" <?php if ($data['show']['status']['clear0']){echo checked;} ?>>
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
      <form name="auto_form" action="auto_form.php" onsubmit="return validateAutoForm()">
        <div class="row">
          <div class="col-75">
            <label>Toggle automatic mode</label>
            <input type=checkbox name="toggle-auto-mode" value="true" <?php if ($data['auto']['enabled']){echo checked;} ?>>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Debug Level</label>
          </div>
          <div class="col-75">
            <input type=text name="show-debug-level" value="<?php echo $data['auto']['level']; ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Duration</label>
          </div>
          <div class="col-75">
            <input type=text name="show-duration" value="<?php echo $data['auto']['duration']; ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Timeout</label>
          </div>
          <div class="col-75">
            <input type=text name="show-timeout" value="<?php echo $data['auto']['timeout']; ?>">
          </div>
        </div>

        <br>
        <div class="row">
          <input type="submit" value="Submit">
        </div>
      </form>
    </div>

    <div class="container">
      <h3>Network and Updates:</h3>
      <form name="update" action="update.php">
      <div class="row">
        <div class="col-75">
          <label>Force Github update</label>
        </div>
        <div class="col-25">
          <input type="submit" value="Update ROS River">
        </div>
      </div>
      </form>
    </div>

    <div class="container">
      <h3>Display Settings</h3>
      <div class="row">
        <div class="col-25">
          <label>Width</label>
        </div>
        <div class="col-75">
          <input type=text name="show-width" value="<?php echo $data['display']['width']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label>Height</label>
        </div>
        <div class="col-75">
          <input type=text name="show-height" value="<?php echo $data['display']['height']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label>Brightness</label>
        </div>
        <div class="col-75">
          
          <input type=number name="show-brightness" value="<?php echo $data['display']['brightness']; ?>" min="1" max="50">
        </div>
      </div>
      <br>
      <div class="row">
        <input type="submit" value="Submit">
      </div>
    </div>

    <div class="container">
      <h3>ROS Settings (Restart Required):</h3>
      <form name="restart_form" action="restart_form.php" onsubmit="return validateRestartForm()">
      <div class="row">
        <div class="col-25">
          <label>ROS Host IP</label>
        </div>
        <div class="col-75">
          <input type=text name="show-host-IP" value="<?php echo $data['settings']['ros']['coreIP']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label>Raspberry PI IP</label>
        </div>
        <div class="col-75">
          <input type=text name="show-host-IP" value="<?php echo $data['settings']['ros']['riverIP']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label>ROS Port</label>
        </div>
        <div class="col-75">
          <input type=text name="show-port" value="<?php echo $data['settings']['ros']['port']; ?>">
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