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
      <h3>Defined Settings:</h3>
      <div class="row">
        <div class="col-50">
          <label>ROS host IP</label>
        </div>
        <div class="col-50">
          <input type="text" placeholder="<?php echo $data["settings"]["ros"]["coreIP"].":".$data["settings"]["ros"]["port"]; ?>" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <label>Display width</label>
        </div>
        <div class="col-50">
          <input type="text" placeholder="<?php echo $data["display"]["width"]; ?>" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <label>Display height</label>
        </div>
        <div class="col-50">
          <input type="text" placeholder="<?php echo $data["display"]["height"]; ?>" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <label>Display height</label>
        </div>
        <div class="col-50">
          <input type="text" placeholder="<?php echo $data["display"]["height"]; ?>" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <label>Display brightness</label>
        </div>
        <div class="col-50">
          <input type=text placeholder="<?php echo $data["display"]["brightness"]; ?>" disabled>
        </div>
      </div>
    </div>
  </div>
</body>