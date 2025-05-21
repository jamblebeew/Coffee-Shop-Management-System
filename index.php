<!DOCTYPE html>
<html>
<head>
  <title>My First PHP Website</title>
</head>
<body>
  <h1>Welcome to My Website</h1>
  

  <?php
    // Display the date with ordinal suffix and time
    echo "<p>Today is " . date("l, F jS, Y \a\\t h:i a") . "</p>";

    // Greeting based on current hour
    $hour = date("H");  // 24-hour format

    if ($hour < 12) {
      $greeting = "Good morning!";
    } elseif ($hour < 18) {
      $greeting = "Good afternoon!";
    } else {
      $greeting = "Good evening!";
    }

    echo "<p>$greeting Welcome to my website!</p>";

    
  ?>
</body>
</html>
