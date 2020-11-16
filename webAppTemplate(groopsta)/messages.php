<?php

  // Header file
  require_once 'header.php';
  
  if(!$loggedin)
  {
    die("</div></body></html>");
  } // end if(!$loggedin)


  // Determine if user is viewing a chat
  // User IS viewing a chat - messages.php?view=[contact]
  if (isset($_GET['view']))
  {
    // Sanitize view
    $view = sanitizeString($_GET['view']);
    // View status is true
    $isView = TRUE;

    // Time function
    $time = time();
    // Insert the viewed user's messages up to the most recent message as viewed into the database
    $query  = "INSERT INTO messages VALUES(NULL, '$user', '$view', '$time', NULL, '$user', FALSE)";
    queryMysql($query);

  } // end if

  // User IS NOT viewing a chat - User is on the general messages page (messages.php)
  else
  {
    // View status is false
    $isView = FALSE;
  } // end else





  // Add message to the database
  if (isset($_POST['text']))
      {
        // Sanitize message
        $text = sanitizeString($_POST['text']);

        // Do if message is not blank
        if ($text != "")
        {

          /* Gets 1 row of all columns in the messages table of the most recently viewed contact of the user sending the message (a.k.a. the contact the user is sending the message to) - This is a workaround as it is the only way to determine the desired recipient of the message as the $view variable cannot be directly obtained or passed to this section */
          $result = queryMysql("SELECT * FROM messages WHERE viewed='$user' ORDER BY id DESC LIMIT 1");
          $num = $result->num_rows;

          /* Gets the intended recipient - Does it this way as the most recently viewed message could either be sent FROM the user or sent TO the user */
          for ($j = 0 ; $j < $num ; ++$j)
          {
            $row = $result->fetch_array(MYSQLI_ASSOC);

            /* If the recipient of the most recently viewed message is the user, then logically the author of the most recently viewed message is the intended recipient of the message being currently sent by the user*/
            if($row['recip'] == $user)
            {
              $recip = $row['auth'];
            } // end if($row['recip'] == $user)

            /* Inversely, if the author of the most recently viewed message is the user, then logically the recipient of the most recently viewed message is the intended recipient of the message being currently sent by the user*/
            else
            {
              $recip = $row['recip'];
            } // end if($row['recip'] == $user) else

          }

          // Time function
          $time = time();

          // Insert message into the database
          $query  = "INSERT INTO messages VALUES(NULL, '$user', '$recip', '$time', '$text', '$user', TRUE)"; 
          queryMysql($query);
          // Refresh page through redirecting to the current chat
          header("Location: messages.php?view=$recip");
        } // end if ($text != "")

    } // end if(isset($_POST['text']))









    /* Start display of the page */




    // Start of recent contacts section


    // Page container
    echo "<div class='messagesContain'><!-- <h3>$name1 Messages</h3> -->";

    // Opening tag for container for messages template & other tags - commented in html below
    echo <<<_MESSAGES
        <!-- Messages Container -->
        <div class="messageContainBox">
          <!-- Recent contacts section -->
          <div class="recent">
            <!-- Name of section div -->
            <div class="sectionName">
              <p>
                Recent
              </p>
            </div> <!-- End sectionName -->
    _MESSAGES;



      /* Get all from messages table where user is the author or recipient AND the user has viewed a message AND the indicator for whether the row is a sent message is false (used to only get purely viewed messages not sent or received ones - eliminates duplicates - chats where there is only a single message, that was sent by the user, still show up as when you send a message the page refreshes and you then view the message that you have just sent) */
      $result = queryMysql("SELECT * FROM messages WHERE auth='$user' || recip='$user' AND viewed='$user' AND send=FALSE ORDER BY id desc");
      $num    = $result->num_rows;

      // Do if the user has any message history
      if($num)
      {
        // The user HAS recent contacts - Used further below
        $empty = FALSE;

        // Loop through messages to determine whether each message is sent by the user or received by the user - Used for display reasons 
        for ($j = 0 ; $j < $num ; ++$j)
        {
          $row = $result->fetch_array(MYSQLI_ASSOC);

          if($row['auth'] != $user)
          {
            $recentArray[$j] = $row['auth'];
          }// end if($row['auth'] != $user)
          else
          {
            $recentArray[$j] = $row['recip'];
          } // end if($row['auth'] != $user) else
        }// end for


        // Remove duplicate values
        $uniqueRecentArray = array_unique($recentArray, SORT_STRING);

        // Display recent contacts
        foreach ($uniqueRecentArray as $value)
        {
          // Do if the user HAS a profile picture
          if (file_exists("$value.jpg"))
          {
            $userImg = "<img class='userImg' src='$value.jpg'>";  // image class here can be found in the styles.css file, not messages.css
          }
          // Do if the user DOES NOT HAVE a profile picture
          else
          {
            $userImg = "<img class='userImg' src='noPic.jpg'>";
          }
          // display contact
          echo "<a href='messages.php?view=$value'><div class='user'>$userImg<p>$value</p></div></a>";
        } // End foreach ($uniqueRecentArray as $value) - display of recent contacts
    

      } // end if($num)

      // The user DOES NOT HAVE any message history
      else
      {
        echo "<div class='user'><p>No recent contacts</p></div>";
        // The user message history indicator IS TRUE
        $empty = TRUE; 
      } // end if($num) else


    // Recent contacts section closing tag and main message section opening tag
    echo "       
          </div><!-- End recent -->
          <div class='messageMain'>          
            <div class='sectionName'>
          "; // end echo








    // End of recent contacts section


    /* --------------------------------------------------------------------------------------------------------------------------------------- */


    // Start of send and receive messages section













    // User IS NOT viewing a contact
    if(!$isView)
    {
      $contact = "Start a Conversation!";
    } // end if(!$isView)

    // User IS viewing a contact
    else
    {
      // The user HAS recent contacts
      if(!$empty)
      {
        // Contact variable is the currently viewed contact
        $contact = $view;

        // Do if the user HAS a profile picture
        if (file_exists("$view.jpg"))
        {
          $contactImg = "<img class='contactImg' src='$view.jpg'>";  // image class here can be found in the styles.css file, not messages.css
        } // end if (file_exists("$view.jpg"))

        // Do if the user DOES NOT HAVE a profile picture
        else
        {
          $contactImg = "<img class='contactImg' src='noPic.jpg'>";
        } // end if (file_exists("$view.jpg")) else

      } //end if(!$empty)

      // The user DOES NOT HAVE recent contacts
      else
      {
        $contact = "Start a Conversation!";
      } //end if(!$empty) else

    } // end if(!$isView) else

    // Close sectionName div and open content div
    echo "
              <p>$contact</p>
            </div> <!-- End sectionName -->          
            <div class='content'>
          ";


    // User IS NOT viewing a contact
    if(!$isView)
    {
      echo "<h4 style='text-align: center'>No messages yet</h4>";
    } // end if(!$isView)

    // User IS viewing a contact
    else
    {
      /* Get all messages where the user is the author or recipient and the selected contact ($view) is the opposite to what the user is, ordered from oldest to newest */
      $message = queryMysql("SELECT * FROM messages WHERE (auth='$user' AND recip='$view') || (recip='$user' AND auth='$view') ORDER BY id ASC");
      $num    = $message->num_rows;

      // If the user HAS received or sent a message/messages with this contact
      if($num)
      {
        //$empty = FALSE;

        // Loop through selected messages and display in the correct style
        for ($j = 0 ; $j < $num ; ++$j)
        {
          $row = $message->fetch_array(MYSQLI_ASSOC);

          // Row is not a view input (an input that solely describes a view of a message)
          if($row['message'] != NULL)
          {
            // Message was sent by the user - Message displays on the right
            if($row['auth'] == $user)
            {
              echo "<div class='message Me'><p>" . $row['message'] . "</p></div><br><br>";
            } // end if($row['auth'] == $user)

            // Message was sent by the contact - Message displays on the left
            else
            {
              echo "<div class='message Else'><p>" . $row['message'] . "</p></div><br><br>";
            } // end if($row['auth'] == $user) else 

          } // end if($row['message'] != NULL)

        }// end for

      } // end if($num)


    } // end if(!$isView) else

    // Close content div and open type div (Section where message form is placed)
    echo "</div> <!-- End content -->
          <div class='type'>
          ";


    // User IS viewing a contact - message form becomes available
    if($isView)
    {
      echo "                
                <form method='POST'>
                  <input class='postMessageText' type='text' name='text' placeholder='Enter a message'>
                  <input class='postMessageBtn' type='submit' value='Send'>
                </form>
              "; // End echo - end of messaage form
    }
    
    // Close type div and messageMain div
    echo "
            </div><!-- End type -->
          </div> <!-- End messageMain -->
          "; // End echo - end type and messageMain divs












    // End of send and receive messages section


    /* --------------------------------------------------------------------------------------------------------------------------------------- */


    // Start of all contacts section











    // Opening div of contacts section and section description section (sectionName)
    echo "
          <div class='contacts'>          
            <div class='sectionName'>
              <p>
                Contacts
              </p>
            </div>
          "; // End echo - close sectionName

    // Get all of the user's friends
    $result = queryMysql("SELECT * FROM friends WHERE user='$user' ORDER BY friend");
    $num    = $result->num_rows;

    // The user HAS at least 1 contact
    if($num)
    {

      // Loop through all contacts and display them
      for ($j = 0 ; $j < $num ; ++$j)
      {
        $value = $row['friend'];

        // Do if the user HAS a profile picture
        if (file_exists("$value.jpg"))
        {
          $userImg = "<img class='userImg' src='$value.jpg'>";  // image class here can be found in the styles.css file, not messages.css
        }
        // Do if the user DOES NOT HAVE a profile picture
        else
        {
          $userImg = "<img class='userImg' src='noPic.jpg'>";
        }

        $row = $result->fetch_array(MYSQLI_ASSOC);

        // Display contact
        echo "<a href='messages.php?view=" . $row['friend'] . "'><div class='user'>$userImg<p>" . $row['friend'] . "</p></div><a>";
      }// end for

    } // end if($num)

    // The user DOES NOT HAVE any contacts
    else
    {
      echo "<div class='user'><p>No friends yet</p></div>";
    }// end if($num) else 


    // Close contacts section and close message container section
    echo "           
          </div> <!-- End contacts -->
        </div> <!-- End messageContainBox -->
    "; // end echo - Main










    // End of all contacts section









    // REASSESS - Old code - Some elements need to be added to the new code from this section 
    /*
    echo "<div class='postedMessagesContain'>";
    date_default_timezone_set('UTC');

    if (isset($_GET['erase']))
    {
      $erase = sanitizeString($_GET['erase']);
      queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$user'");
    }
    
    $query  = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
    $result = queryMysql($query);
    $num    = $result->num_rows;
    
    for ($j = 0 ; $j < $num ; ++$j)
    {
      $row = $result->fetch_array(MYSQLI_ASSOC);

      if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user)
      {
        echo date('M jS \'y g:ia:', $row['time']);
        echo " <a href='messages.php?view=" . $row['auth'] .
             "'>" . $row['auth']. "</a> ";

        if ($row['pm'] == 0)
          echo "wrote: &quot;" . $row['message'] . "&quot; ";
        else
          echo "whispered: <span class='whisper'>&quot;" .
            $row['message']. "&quot;</span> ";

        if ($row['recip'] == $user)
          echo "[<a href='messages.php?view=$view" .
               "&erase=" . $row['id'] . "'>erase</a>]";

        echo "<br><br>";
      }
    }

  if (!$num)
    echo "<br><span class='info'>No messages yet</span><br><br>";

  echo "<br><a class='eraseBtn' data-role='button'
        href='messages.php?view=$view'>Refresh messages</a></div></div>"; */
















  /* End of page */
  die("</div></body></html>");



?>

    </div><br>
  </body>
</html>
