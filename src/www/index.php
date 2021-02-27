<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <script src="formValidate.js"></script>
  <script src="ping.js"></script>
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
            <input type=number name="show-status" value="<?php echo $data['show']['status']['msg']; ?>" min=0 max=992>
          </div>
          <div class="col-25">
            <input type="color" name="show-status-color" value="<?php echo $data['show']['status']['color']; ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-75">
            <label>Toggle heartbeat</label>
          </div>
          <div class="col-25">
            <label class="switch">
              <input type=checkbox name="toggle-heartbeat" value="true" <?php if ($data['settings']['heartbeat']['enabled']){echo checked;} ?>>
              <span class="slider round"></span>
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col-75-one-Line">
            <label>Toggle Clear Zero</label>
          </div>
          <div class="col-25-one-Line">
            <label class="switch">
              <input type=checkbox name="toggle-clear-zero" value="true" <?php if ($data['show']['status']['clear0']){echo checked;} ?>>
              <span class="slider round"></span>
            </label>
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
      <form name="auto_form" action="auto_form.php">
        <div class="row">
          <div class="col-75">
            <label>Toggle automatic mode</label>
          </div>
          <div class="col-25">
            <label class="switch">
              <input type=checkbox name="toggle-auto-mode" value="true" <?php if ($data['auto']['enabled']){echo checked;} ?>>
              <span class="slider round"></span>
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Debug Level</label>
          </div>
          <div class="col-75">
            <input type=number name="show-debug-level" value="<?php echo $data['auto']['level']; ?>" min=0 max=8>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Duration</label>
          </div>
          <div class="col-75">
            <input type=number name="show-duration" value="<?php echo $data['auto']['duration']; ?>" min=0 max=60>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Timeout</label>
          </div>
          <div class="col-75">
            <input type=number name="show-timeout" value="<?php echo $data['auto']['timeout']; ?>" min=1 max=300>
          </div>
        </div>

        <br>
        <div class="row">
          <input type="submit" value="Submit">
        </div>
      </form>
    </div>

    <div class="container">
      <h3>Display Settings</h3>
      <form name="display_form" action="display_form.php">
        <div class="row">
          <div class="col-25">
            <label>Width</label>
          </div>
          <div class="col-75">
            <input type=number name="show-width" value="<?php echo $data['display']['width']; ?>" min=1 max=255>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Height</label>
          </div>
          <div class="col-75">
            <input type=number name="show-height" value="<?php echo $data['display']['height']; ?>" min=1 max=255>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label>Brightness Percent:</label>
          </div>
          <div class="col-75">
            <input type=number name="show-brightness" value="<?php echo $data['display']['brightness']; ?>" min="1" max="50">
          </div>
        </div>
        <br>
        <div class="row">
          <input type="submit" value="Submit">
        </div>
      </form>
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
          <input type=text name="show-ros-IP" value="<?php echo $data['settings']['ros']['riverIP']; ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label>ROS Port</label>
        </div>
        <div class="col-75">
          <input type=number name="show-port" value="<?php echo $data['settings']['ros']['port']; ?>" min=0 max=99999>
        </div>
      </div>
      
      <br>
      <div class="row">
        <input type="submit" value="Submit and Reboot">
      </div>
      </form>
    </div>

    <div class="container">
      <h3>Updates:</h3>
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
      <h3>Logs:</h3>
      <form name="showLog" action="showLog.php">
      <div class="row">
        <div class="col-50">
          <label>Open show Log</label>
        </div>
        <div class="col-25">
        <label>Errors: <?php
              $linecount = 0;
              $file = "/home/ubuntu/catkin_ws/src/river/src/show.log";
              if (file_exists($file)){
                $myfile = fopen($file, "r");
                while(!feof($myfile)) {
                    $line = fgets($myfile);
                    $linecount++;
                }
                fclose($myfile);
                echo $linecount - 1;
              }
              else{
                echo "No File.";
              }
          ?></label>
        </div>
        <div class="col-25">
          <input type="submit" value="Open">
        </div>
      </div>
      </form>
      <form name="startUpLog" action="startUpLog.php">
      <div class="row">
        <div class="col-50">
          <label>Open start up Log</label>
        </div>
        <div class="col-25">
          <label>Errors: <?php
              $linecount = 0;
              $file = "/home/ubuntu/catkin_ws/src/river/src/startUp.log";
              if (file_exists($file)){
                $myfile = fopen($file, "r");
                while(!feof($myfile)) {
                    $line = fgets($myfile);
                    $linecount++;
                }
                fclose($myfile);
                echo $linecount - 1;
              }
              else{
                echo "No File.";
              }
          ?></label>
        </div>
        <div class="col-25">
          <input type="submit" value="Open">
        </div>
      </div>
      </form>
      <form name="mainLog" action="mainLog.php">
      <div class="row">
        <div class="col-50">
          <label>Open main Log</label>
        </div>
        <div class="col-25">
        <label>Errors: <?php
              $linecount = 0;
              $file = "/home/ubuntu/catkin_ws/src/river/src/main.log";
              if (file_exists($file)){
                $myfile = fopen($file, "r");
                while(!feof($myfile)) {
                    $line = fgets($myfile);
                    $linecount++;
                }
                fclose($myfile);
                echo $linecount - 1;
              }
              else{
                echo "No File.";
              }
          ?></label>
        </div>
        <div class="col-25">
          <input type="submit" value="Open" <?php if (!file_exists("/home/ubuntu/catkin_ws/src/river/src/main.log")){ echo "disabled";} ?>>
        </div>
      </div>
      </form>
    </div>

  </div>
</body>