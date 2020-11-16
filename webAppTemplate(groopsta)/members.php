<?php // members.php
  require_once 'header.php';

  /* Don't show page if not logged in */
  if (!$loggedin) die("</div></body></html>");


  /* Check if user is logged in an then retrieve the profile contents */ 
  if (isset($_GET['view']))
  {
    $view = sanitizeString($_GET['view']);
    
    if ($view == $user)
    {
      $name = "Your";

      echo <<<_USERMAIN
        <div class="memberProfileContain">
        <div class="memberProfile">
        <h2>$name Profile</h2>
        _USERMAIN;
      showProfile($view);
      echo <<<_USERMAINEND
          <a data-role='button' href='profile.php'>Edit Profile</a>
          </div>
          <div class="profileButtons">
          <a data-role='button' href='messages.php?view=$view'>View $name messages</a>
          <a data-role='button' href='friends.php'>View $name friends</a>
          <a data-role='button' href='logout.php'>Log Out</a>
          </div>
          </div>
          _USERMAINEND;
      die("</body></html>");
      require_once 'footer.php';
      /* require_once 'footer.php';   ----- This won't work here */

    } // end $view == $user if
    else
    {
      $name = "$view's";
      
      echo <<<_USERMAIN
          <div class="memberProfileContain">
          <div class="memberProfile">
          <h2>$name Profile</h2>
          _USERMAIN;
      showProfile($view);
      echo <<<_USERMAINEND
          </div>
          <div class="profileNavContain">
          <nav>
            <a href="libraries.php">Libraries</a>
            <a href="#friends">Friends</a>
          </nav>
          </div>
          _USERMAINEND;
      /* require_once 'footer.php';   ----- This won't work here */

  
      if(isset($_POST['button1']))
      {
        $view = sanitizeString($view);
        $user = sanitizeString($user); 

        $result = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='$view'");     // $add = person being added      $user = the person adding a friend
        if (!$result->num_rows)
        {
          queryMysql("INSERT INTO friends VALUES ('$user', '$view')");
          echo "<h3>Friend added</h3>";
        } // end !result->num_rows if 

      } // end addFriend function

      echo "<div class='addFriend'><form method='post'><input class='addFriendBtn' type='submit' name='button1' value='Add Friend'/></form>"; 

      die("</div></div></body></html>");

    } // end else
  } // end is set(view) if










/* This is currently un-used as friends.php is used instead

/* 2nd half - This is the page for checking other members a.k.a. members.php instead of members.php?view=$user */

  /* Add friend */
  if (isset($_GET['add']))
  {
    $add = sanitizeString($_GET['add']);

    $result = queryMysql("SELECT * FROM friends WHERE user='$add' AND friend='$user'");
    if (!$result->num_rows)
      queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
  }

  /* remove friend */
  elseif (isset($_GET['remove']))
  {
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
  }

  $result = queryMysql("SELECT user FROM members ORDER BY user");
  $num    = $result->num_rows;

  echo "<div class='otherMembersContain'><h3>Other Members</h3><ul>";

  for ($j = 0 ; $j < $num ; ++$j)
  {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['user'] == $user) continue;
    
    echo "<li><a href='members.php?view=" .
      $row['user'] . "'>" . $row['user'] . "</a>";
    $follow = "follow";

    $result1 = queryMysql("SELECT * FROM friends WHERE
      user='" . $row['user'] . "' AND friend='$user'");
    $t1      = $result1->num_rows;
    $result1 = queryMysql("SELECT * FROM friends WHERE
      user='$user' AND friend='" . $row['user'] . "'");
    $t2      = $result1->num_rows;

    if (($t1 + $t2) > 1) echo " &harr; is a mutual friend";
    elseif ($t1)         echo " &larr; you are following";
    elseif ($t2)       { echo " &rarr; is following you";
                         $follow = "recip"; }
    
    if (!$t1) echo " [<a
      href='members.php?add=" . $row['user'] . "'>$follow</a>]";
    else      echo " [<a
      href='members.php?remove=" . $row['user'] . "'>drop</a>]";
  }

  echo "<div>";
?>
    </ul></div>
  </body>
</html>
