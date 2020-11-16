<?php // Example 26-4: index.php    groopsta.com
  session_start();
  
  // Home indicator is true
  $home = TRUE;

  // Header file
  require_once 'header.php';

  /* Non-logged in homescreen */
  if(!$loggedin)
  {
    // Left side of non-logged in homescreen
    echo <<<_GUESTNAV1
          <div class="homeContain">
          <div class="leftNotLoggedIn">
              <h1>Groopsta</h1>
              <img src="Assets/Images/logo3.jpg">
              <h2>Where advice meets reality</h2>
              <div class="homeDescription">
                <!-- <h3>What is Groopsta?</h3>
                <p>Groopsta is an online community of ordinary people, focussed around sharing their advice on medical issues based
                on their own experiences, so that you can understand what advice works best.</p> -->
              </div> <!-- End homeDescription -->
          </div> <!-- End leftNotLoggedIn -->
          <div class="rightNotLoggedIn">
      _GUESTNAV1;

    // Search topics for non-logged in home page - redirects to topics.php
    echo <<<_GUESTNAV2
          <div class="homeSearch">
          <h2>Get Started</h2>
              <form action="topics.php" method="GET">
                  <input class="homeSearchText" type="text" placeholder="Find an answer to your problem" name="query"><br>
                  <input class="homeSearchBtn" type="submit" value="Search">
              </form>
          </div> <!-- End homeSearch -->
    _GUESTNAV2;

    // Home log in section
    echo <<<_INDEXLOGIN
          <div class="indexLogin">
          <h3>Login</h3>
          <form method='post' action='login.php'>
            <div class="textInputs">
              <input type="text" name='user' maxlength='16' placeholder='Username'>
              <input type='text' name='pass' maxlength='16' placeholder='Password'>
            </div>
            <div class="signInBtnHome">
              <input type='submit' value='Login'>
            </div>
          </form>
          </div> <!-- End indexLogin -->
    _INDEXLOGIN;

    // Home sign up section
    echo <<<_INDEXSIGNUP
          <div class="indexSignUp">
          <h3>Sign Up</h3>
          <form method='post' action='signup.php'>
            <div class="textInputs">
              <input type="text" name='user' maxlength='16' placeholder='Username' required>
              <input type='text' name='pass' maxlength='16' placeholder='New Password' required>
            </div>
            <div class="textInputs">
              <input type="email" name='email' maxlength='320' placeholder='Email' required>
              <input type='text' maxlength='16' placeholder='Re-type Password' required>
            </div>
            <div class="signInBtnHome">
              <input type='submit' value='Sign Up'>
            </div>
            <h4>$error$error2</h4>
          </form>
          </div><!-- End indexSignUp -->
          </div><! -- End rightNotLoggedIn -->
          </div><!-- End homeContain -->
    _INDEXSIGNUP;

    // Footer
    echo <<<_END
        <footer>
            <h3>Groopsta &copy; 2020</h3>
        </footer>
        </body>
        </html>
    _END;
    /* End page */

  } // end if(!$loggedIn)

  /* End non-logged in homescreen */








  /* ------------------------------------------------------------------------------------------------------------------------------------------- */








  /* Start logged in home screen */
  else 
  {
  
    /* Logged in home container */
    echo <<<_HOMEMAIN
        <div class="loggedInHomeContain">
    _HOMEMAIN;



      // Recent topics container - Topics recently viewed by the user appear here
      echo <<<_RECENTTOPICS
          <div class="recentTopics">
            <h2>Recently viewed</h2>
      _RECENTTOPICS;

      // Select all recently viewed
      $query  = "SELECT viewed FROM topics WHERE viewed != '$null' AND auth = '$user' ORDER BY id desc";
      $recentView = queryMysql($query);
      $num    = $recentView->num_rows;

      // No topics recently viewed
      if(!$num)
      {
        echo "<h2>You have not viewed any topics yet<h2><br><a href='topics.php'>Get started here</a>";
      }

      // recently viewed topics
      else
      {

        // print recently viewed topics
        for ($j = 0 ; $j < 4 ; ++$j)
        {
          $row = $recentView->fetch_array(MYSQLI_ASSOC);
          /*$row2 = $bodyRegionDisplay->fetch_array(MYSQLI_ASSOC);*/
                
          echo "<div class='recentView'><a href='topics.php?view=" . $row['viewed'] . "'><h3>" . $row['viewed'] . "</h3></a></div>";


        } // end for

      } // end if(!$num) else

      // end recent topics container
      echo <<<_RECENTTOPICSEND
          </div> <!-- End recentTopics -->
      _RECENTTOPICSEND;


      // REASSESS - add functionality
      // Trending topics section 
      echo <<<_TRENDING
          <div class="trending">
            <h2>Trending</h2>
            <div class='test'>
            </div>
      _TRENDING;

      // End trending section
      echo <<<_TRENDINGEND
          </div> <!-- End trending -->
      _TRENDINGEND;


      // Start home logged in topics search
      echo <<<_SEARCH
          <div class="searchTopics">
            <h2>Search Topics</h2>
      _SEARCH;

      // Search form
      echo <<<_LIBINPUT
            <div class="homeSearchTopic">
            <h4>Find an answer to your problem</h2>
            <form method="GET">
              <input class="homeSearchTextTopic" type='text' name='query' placeholder="Enter a Topic">
              <input class="inLogHomeSearchBtn" type="submit" value="Search">
              <h3>$error</h3>
            </form>

      _LIBINPUT;


      // find search query
      if (isset($_GET['query']))
      {
        $query = $_GET['query'];
        $query = sanitizeString($query);

        header("Location: topics.php?query=$query");

      } // end if (isset($_GET['addTopic']))  


      // End home logged in topics search
      echo <<<_SEARCHEND
          </div> <!-- End searchTopics -->
      _SEARCHEND;
    

    /* End page */
    echo <<<_ENDMAIN
        </div> <!-- End loggedInHomeContain -->
        <footer>
            <h3>Groopsta &copy; 2020</h3>
        </footer>
        </body>
        </html>
    _ENDMAIN;
}
/* End logged in home screen */

?>
