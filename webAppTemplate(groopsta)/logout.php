<?php // Example 26-12: logout.php
  require_once 'header.php';

  if (isset($_SESSION['user']))
  {
    destroySession();
    echo "<br><div class='centerN'>You have been logged out. Please <a href='index.php'>click here</a> 
    to refresh the screen and complete the logout</div>";
  }
  else
  {
    echo "<div class='center'>You cannot log out because you are not logged in</div>";
  }

?>
    </div>
  </body>
</html>
