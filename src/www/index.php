<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <script>
  </script>
  <?php
    echo "php works";
  ?>
</head>
<body>

<h1>This is a heading</h1>
<p>This is a paragraph.</p>

<form action="restart_form.php"> 
    First name:
    <input type="text" name="firstname"> 
    
    <br> Last name:
    <input type="text" name="lastname"> 

    <input type="hidden" name="form_submitted" value="1" />

    <input type="submit" value="Submit">

</form>
</body>
</html>