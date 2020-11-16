<?php // topics.php 

  // header file
  require_once 'header.php';


  date_default_timezone_set('UTC'); // REASSESS


  // Topic page container
  echo <<<_LIBRARYSTART
        <div class='topicsContainer'>
    _LIBRARYSTART;







  /* ------------------------------------------------------------------------------------------------------------------------------------------- */







  // START individual topic page a.k.a. topics.php?view=[topic]

  // Determine if the user is viewing a topic
  if (isset($_GET['view']))
  {
    // Determine what topic the user is looking at
    $view = sanitizeString($_GET['view']);

    // View indicator is true
    $isView = TRUE;


    // time function
    $time = time();
    // Add the topic status as viewed by the user
    $query  = "INSERT INTO topics VALUES(NULL, '$user', NULL, NULL, NULL, '$time', '$view')"; //REASESS 
    queryMysql($query);



    // Add method form input to the database
    if (isset($_POST['topicmethodtitle']))
    {
      // Sanitize input - function found in functions.php file
      $topicMethodTitle = sanitizeString($_POST['topicmethodtitle']);
      $topicMethodDescribe = sanitizeString($_POST['topicmethoddescribe']);


      /* Determine if method has already been added */
      $query  = "SELECT topicmethodtitle FROM topics WHERE topicname = '$view' AND topicmethodtitle = '$topicMethodTitle'"; 
      $isAdded = queryMysql($query);

      $num = $isAdded->num_rows;

      // Method already added
      if($num)
      {
        echo "<h3 style='text-align: center;'>$topicMethodTitle has already been added as a method</h3>";
      } // end if

      // Method NOT already added
      else
      {
        // Time function
        $time = time();
        // Insert method into the database 
        $query  = "INSERT INTO topics VALUES(NULL, '$user', '$view', '$topicMethodTitle', '$topicMethodDescribe', '$time', NULL)"; 
        queryMysql($query);
      } // end else
            

    } // end if (isset($_POST['topicmethodtitle']))

    /* END determine if method has already been added */


    // Header
    echo "<header><h1>$view</h1></header>";

    // Top methods title
    echo "<h2>Top Methods</h2>";

    /* Viewing of individual topics error checks */
      //substr($view, strpos($view, "/") + 1); - Former way of doing it, deprecated - Good trick to know though

      $query  = "SELECT topicname FROM topics WHERE topicname = '$view'";
      $viewTestCompare = queryMysql($query);
      $num    = $viewTestCompare->num_rows;

      // Topic doesn't exist
      if(!$num)
      {
        echo "This topic name doesn't exist yet";
        // End page
        die("</div></body></html>");
      } // end if(!$num)

      // Topic DOES exist
      else
      {
        /* Start individual topics display */

          // Get contents of display of individual libraries
          $query  = "SELECT topicmethodtitle FROM topics WHERE topicname = '$view' AND topicmethodtitle IS NOT NULL";
          $indiMethod = queryMysql($query);
          // Determine if methods have been added to the topic yet
          $num    = $indiMethod->num_rows;

          // No methods added yet
          if (!$num)
          {
            echo "<div class='noLibraries'><h2>No methods have been added yet, be the first to contribute!</h2></div>";

            // If the user is logged in, they have permission to add a method
            if($loggedin)
            {
              // Add a method form
              echo <<<_LIBINPUT
                <div class="topicFormContain">
                <h3>Add a method for this topic</h3>
                <form method='POST' action="topics.php?view=$view">
                  <input class="topicText" type='text' name='topicmethodtitle' placeholder="Enter your method"><br>
                  <textarea name='topicmethoddescribe'></textarea><br>
                  <input class="createTopicBtn" type="submit" value="Add Method">
                  <h3>$error</h3>
                </form>
                </div>
                _LIBINPUT;

                die("</div></body></html>");

            } // end if($loggedin)

          } // end if(!$num)
          
          // Methods HAVE been added
          else
          {

            //Topics container
            echo "<div class='topicN'>";

            // Loop according to the amount of methods there are for this topic
            for ($j = 0 ; $j < $num ; ++$j)
            {

              // Topicmethodtitle
              $row = $indiMethod->fetch_array(MYSQLI_ASSOC);

              // Assigning a variable to the topic method title - Used for mysql command below
              $topicmethodtitleDisplay = $row['topicmethodtitle'];

              // Get the description belonging to the method
              $query = "SELECT topicmethoddescribe FROM topics WHERE topicmethodtitle = '$topicmethodtitleDisplay' AND topicname = '$view'";
              $indiMethodDescribe = queryMysql($query);
              $num2 = $indiMethodDescribe->num_rows;

              // No description attached to the method
              if(!$num2)
              {
                $errorMethod = "There is no description for this method";
              }

              // Row for topicmethoddescribe column
              $row2 = $indiMethodDescribe->fetch_array(MYSQLI_ASSOC);
              

              // Assign variable to description of method - Used for mysql command below
              $topicmethoddescribeDisplay = $row2['topicmethoddescribe'];


              $query  = "SELECT auth FROM topics WHERE topicmethoddescribe = '$topicmethoddescribeDisplay' AND topicmethodtitle = '$topicmethodtitleDisplay' AND topicname = '$view'";
              $indiMethodAuthor = queryMysql($query);

              $row3 = $indiMethodAuthor->fetch_array(MYSQLI_ASSOC);




              // Display of method titles, method descriptions and method authors 

              echo "<button class='collapsible'><i class='material-icons'>arrow_drop_down</i>" . $row['topicmethodtitle'];

              echo "<a id='up' class='upDownVote' href='index.php'><i class='material-icons'>arrow_downward</i></a><a id='down' class='upDownVote' href='index.php'><i class='material-icons'>arrow_upward</i></a>";

              echo "</button><div class='methodContent'>";
              echo "<div class='methodIndiContent'>";

              echo "<h5>Added by: " . $row3['auth'] . " - " . date('M jS \'y g:ia', $row['time']) . "</h5><h4>Description</h4><p>" . $row2['topicmethoddescribe'] . "$errorMethod<!-- Display error message if there is one --></p></div></div>"; //REASSESS DATE - NOT WORKING & ADD section for comments on the description
  

              
            } // end for


            echo "</div>"; // End librariesN


            /* Collabsible button javascript code */
            echo <<<_JSCRIPT
                  <script>
                  var coll = document.getElementsByClassName("collapsible");
                  var i;
                  for (i = 0; i < coll.length; i++) {
                    coll[i].addEventListener("click", function() {
                      this.classList.toggle("active");
                      var content = this.nextElementSibling;
                      if (content.style.maxHeight){
                        content.style.maxHeight = null;
                      } else {
                        content.style.maxHeight = content.scrollHeight + "px";
                      } 
                    });
                  }
                  </script>
              _JSCRIPT;


          } // end if(!num) else - (Methods HAVE been added else statement)


        // If the user is logged in the add methods form becomes available
        if($loggedin)
        {
          // Add method Form
          echo <<<_METHODFORM
              <div class="topicFormContain">
              <h3>Add a Method</h3>
              <form method='post' action="topics.php?view=$view">
                <input class="topicText" type='text' name='topicmethodtitle' placeholder="Method"><br>
                <textarea name='topicmethoddescribe' placeholder="Description"></textarea><br>
                <button class="createTopicBtn" type="submit"><i class='material-icons'>add</i></button>
              </form>
              <h3>$error</h3>
              </div>
              _METHODFORM;

        } // end if($loggedin)


        // End page
        die("</div></body></html>");
        
      } // end if(!$num) else - (Topic DOES exist else statement)

      /* End individual topics display */




  } // end if(isset('view'))

  // END individual topic page



  // The user is viewing the general topics page, not an individual topic page
  else
  {
    $isView = FALSE;
  }





























  /* --------------------------------------------------------------------------------------------------------------------------------------- */



































  /* Display all topics a.k.a. main topics page (topics.php) */

  // The user is not viewing a topic
  if(!$isView)
  {

    // Only available to logged in users
    if($loggedin)
    {

      /* Input topic into the database */
      if (isset($_POST['addTopic']))
      {

        // Sanitize input
        $topicName = sanitizeString($_POST['addTopic']);

        /* Test if topic has already been posted */
        $query  = "SELECT topicname FROM topics WHERE topicname = '$topicName'"; 
        $isAdded = queryMysql($query);

        // Find number of rows of data retrieved
        $num = $isAdded->num_rows;

        // Topic already exists
        if($num)
        {
            echo "<h3 style='text-align: center;'>$topicName has already been added</h3>";
        } // end if
        
        // Topic DOESN'T exist - The input can now be inputted into the database
        else
        {
          // time function
          $time = time();

          // Insert topic name into the database
          $query  = "INSERT INTO topics VALUES(NULL, '$user', '$topicName', NULL, NULL, '$time', NULL)";  
          queryMysql($query);
          header("Location: topics.php?view=$topicName");
        } // end if(!$num) else - Topic DOESN'T EXIST

        /* END Test if topic has already been posted */


      } // end if(isset($_POST['addTopic']))

      /* END input topic into the database */

    } // end if($loggedin)


     

    /* START display of topics */


    // Get topic names
    $query = "SELECT topicname FROM topics WHERE topicmethodtitle IS NULL AND topicmethoddescribe IS NULL AND viewed IS NULL ORDER BY topicname"; 
    $topicname = queryMysql($query);
    $num = $topicname->num_rows;


    echo "<header><h1>Topics</h1></header>";

    // Search for topics form
    echo <<<_LIBINPUT
        <div class="topicFormContain">
        <form method="GET">
          <input class="topicText" type='text' name='query' placeholder="Find a Topic">
          <button class="createTopicBtnTopic" type="submit">Search</button>
          <h3>$error</h3>
        </form>
    _LIBINPUT;

    // Get topic search query and return result
    if (isset($_GET['query']))
    {

      // Get query using $_GET method - gets value sent over search form
      $query = $_GET['query']; 

      $query = sanitizeString($query);  
         
      $min_length = 1;
        
      // if query length is more or equal to minimum length then 
      if(strlen($query) >= $min_length)
      { 
               
        $raw_results = queryMySQL("SELECT * FROM topics WHERE (topicmethodtitle IS NULL AND `topicname` LIKE '%".$query."%')") or die(mysql_error());
            
        // if one or more rows are returned do following                  
        if(mysqli_num_rows($raw_results) > 0)
        { 
                 
          while($results = mysqli_fetch_array($raw_results))
          {
                
            if($loggedin)
            {
              // posts results gotten from database
              echo "<span><a class='topicResultLink' href='topics.php?view=" . $results['topicname'] . "'>" . $results['topicname'] . "</a></span><br>";
                      
            } // end if($loggedin)
            else
            {
              echo "<span><a class='topicResultLink' href='topics.php?view=" . $results['topicname'] . "'>" . $results['topicname'] . "</a></span><br>";

              die("</div></body></html>");
            } // end end if($loggedin) else
          
          } // end while
                 
        } // end if(mysqli_num_rows($raw_results) > 0)

        // No rows returned
        else
        {
          if($loggedin)
          {
            echo "No results";
          } // end if($loggedin)
          // End the page if the user isn't logged in
          else
          {
            echo "No results";

            // End page
            die("</div></body></html>");
          } // end if($loggedin) else

        } // end if(mysqli_num_rows($raw_results) > 0) else - No rows returned section
             
      } // end if(strlen($query) >= $min_length)

    } // end if (isset($_GET['query']))

    /* END get and return query execution */


    // Close topic form container div
    echo <<<_LIBINPUT2
        </div> <!-- End topicFormContain -->
    _LIBINPUT2;




    // Topics page container
    echo "<div class='topicN'>";


    // There are no topics already posted - pretty much a failsafe as there are obviously topics already posted
    if (!$num)
    {
        echo "<div class='noTopics'><h2>No topics created yet</h2></div>";
    } // end if(!$num) - No topics posted section



    // Loop according to the amount of topics there are
    for ($j = 0 ; $j < $num ; ++$j)
    {
      $row = $topicname->fetch_array(MYSQLI_ASSOC);
          
      echo "<button class='collapsible'><i class='material-icons'>arrow_drop_down</i><a href='topics.php?view=" . $row['topicname'] . "'>" . $row['topicname'] . "</a>";

      // Updvote/Downvote
      echo "<a id='up' class='upDownVote' href='index.php'><i class='material-icons'>arrow_downward</i></a><bdi class='upDownVote'>1</bdi><a id='down' class='upDownVote' href='index.php'><i class='material-icons'>arrow_upward</i></a>";

      // Upvote/Downvote buttons are functional if the user is logged in
      if($loggedin)
      {
        // make the up/down vote buttons not function for guest users
      }

        
      // Close button tag and display the top 3 methods for each topic
      echo "</button><div class='methodContent'><h4>Top 3 methods:</h4><ul><li>Lorem ipsum...</li><li>Lorem ipsum...</li><li>Lorem ipsum...</li></ul></div>"; // add functionality to 'top description'

    } // END loop according to the amount of topics there are


    echo "</div>"; // End topicN


    // Collapsible button javascript code - REASSESS (Move to javascript file)
    echo <<<_JSCRIPT
              <script>
              var coll = document.getElementsByClassName("collapsible");
              var i;
              for (i = 0; i < coll.length; i++) {
                coll[i].addEventListener("click", function() {
                  this.classList.toggle("active");
                  var content = this.nextElementSibling;
                  if (content.style.maxHeight){
                    content.style.maxHeight = null;
                  } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                  } 
                });
              }
              </script>
            _JSCRIPT;    

    // Only allow logged in users to add a topic
    if($loggedin)
    {
      echo <<<_LIBINPUT2
            <div class='topicFormContain'>
            <h3>Can't find a topic?</h3>
            <form method="POST">
              <input class="topicText" type='text' name='addTopic' placeholder="Add a Topic">
              <button class="createTopicBtnTopic" type="submit">Add</button>
              <h3>$error</h3>
            </form>
            </div> <!-- End topicFormContain -->
        _LIBINPUT2;
    } // end if($loggedin)



     
    // End page 
    die("</div></body></html>");

  } // end if(!$isView) else a.k.a. Display all topics a.k.a. Main topics page (topics.php)   


      


?>
