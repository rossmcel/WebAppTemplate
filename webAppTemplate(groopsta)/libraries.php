<?php // libraries.php 
  require_once 'header.php';
  
  if (!$loggedin) die("</div></body></html>");


  if (isset($_GET['view']))
  {
    $view = sanitizeString($_GET['view']);

    $null = "blank"; // Used for subliblink column when the row is a library name
                      //reassess this - potential for a bug


    echo <<<_LIBRARYSTART
        <div class='libraryContainer'>
        <header><h1>Discover</h1></header>
    _LIBRARYSTART;
















    
    /* -----------------------------------------------------------------------------------------------------*/

    /* Start $view == $user a.k.a Viewing your own libraries */
    if ($view == $user)
    {
      $GLOBALS['viewPermanent'] = $view;

      $topicname = array(queryMysql("SELECT topicname FROM topics WHERE auth='$view' AND topicmethodtitle = '$null' AND topicmethoddescribe = '$null'"));

      $query  = "SELECT topicname FROM topics WHERE auth='$view' AND topicmethodtitle = '$null' AND topicmethoddescribe = '$null'"; 
      // reassess having both of these


      $topicname = queryMysql($query);
      $num    = $topicname->num_rows;

      
      /*$libraryLink = queryMysql("SELECT subliblink FROM libraries WHERE sublibname ='$library'");*/

      echo "<div class='topicN'><h3>Topics</h3><ul>"; //library names print container

      for ($j = 0 ; $j < $num ; ++$j)
      {
        $row = $topicname->fetch_array(MYSQLI_ASSOC);
          
        echo "<li><a href='libraries.php?view=" .
          $view . "/" . $row['topicname'] . "'>" . $row['topicname'] . "</a>";

        /*echo "<h3>" . $row['subliblink'] . "</h3>";*/

        
      } // end print library names for loop

       echo "</ul>"; // End print library names list

      echo "</div>"; // End topicN

      if (!$num)
      {
        echo "<div class='noTopics'><h2>No topics Created Yet</h2></div>";
      } // end if(!$num)


    


      if (isset($_POST['addTopic']))
      {
        $topicName = sanitizeString($_POST['addTopic']);

        /*$libNameCompare = queryMysql("SELECT * FROM libraries WHERE sublibname='$libname'"); */

        
        $time = time();
        $query  = "INSERT INTO topics VALUES(NULL, '$view', '$topicName', '$null', '$null', $time)";  
        queryMysql($query);
        header("Location: libraries.php?view=$user");
        

      }


      echo <<<_LIBINPUT
          <div class="libraryFormContain">
          <h2>Add a Topic</h2>
          <form method='post' action='libraries.php?view=$user'>
            <input class="libraryText" type='text' name='addTopic' placeholder="Enter a Name for Your Library"><br>
            <input class="createLibBtn" type="submit" value="Create">
            <h3>$error</h3>
          </form>
          </div>
          _LIBINPUT;

      die("</div></body></html>");

    } // end if($view == $user)

    /* End $view == $user a.k.a Viewing your own Libraries */
    /* -----------------------------------------------------------------------------------------------------*/


























    else //if($view == $user) else   -   a.k.a. $view != $user
    {












      /* -----------------------------------------------------------------------------------------------------*/

      /* Individual Libraries */




      /* Individual Libraries Error checks */

      // Break down view to determine the library owner and library name
      $libraryName = substr($view, strpos($view, "/") + 1);
      $libraryName = sanitizeString($libraryName);

      $libraryOwner = strtok($view, "/");
      $libraryOwner = sanitizeString($libraryOwner);


      // Check if Library Owner is a member (error check)
      $libraryOwnArray = queryMySQL("SELECT user FROM members WHERE user='$libraryOwner'");

      if ($libraryOwnArray->num_rows == 0)
      {
        echo "No user found for this library";
        die("</div></body></html>");
      }
   
      // Check if Library Name is in the database
      $query  = "SELECT topicname FROM topics WHERE auth='$libraryOwner'";
      $result = queryMysql($query);
      $num    = $result->num_rows;
      $library = array($num);
    
      for ($j = 0 ; $j < $num ; $j++)
      {
        $row = $result->fetch_array(MYSQLI_ASSOC);

        $library = $row['topicmethodtitle'];

        if($library == $libraryName)
        {
          $libraryName = $library;
          break;
        }

      } // end Library Name for

      
      // Put together error-checked Library Owner and Library Name variables 
      $viewType = $libraryOwner . "/" . $libraryName;







      // Check error-checked $view ($viewTest) against actual $view variable i.e. viewing an individual library
      if($view == $viewType) 
      {

        


        if (isset($_POST['topicmethodtitle']))
        {
      
          $topicMethodTitle = sanitizeString($_POST['topicmethodtitle']);
          $topicMethodDescribe = sanitizeString($_POST['topicmethoddescribe']);

          $time = time();
          $query  = "INSERT INTO topics VALUES(NULL, '$libraryOwner', '$libraryName', '$topicMethodTitle', '$topicMethodDescribe', $time)";  
          queryMysql($query);
          header("Location: libraries.php?view=$libraryOwner/$libraryName");
          

        }


        // Display content of individual libraries
        $indiMethod = array(queryMysql("SELECT topicmethodtitle FROM topics WHERE topicname = '$libraryName' AND auth='$libraryOwner'"));

        $query  = "SELECT topicmethodtitle FROM topics WHERE topicname = '$libraryName' AND auth='$libraryOwner'";
        $indiMethod = queryMysql($query);
        $num    = $indiMethod->num_rows;


        if (!$num)
        {
          echo "<div class='noLibraries'><h2>No methods have been added yet, be the first to contribute!</h2></div>";

           echo <<<_LIBINPUT
            <div class="libContentFormContain">
            <h3>Add a method for this topic</h3>
            <form method='post' action='libraries.php?view=$libraryOwner/$libraryName'>
              <input class="libraryText" type='text' name='topicmethodtitle' placeholder="Enter your method"><br>
              <textarea name='topicmethoddescribe'></textarea><br>
              <input class="addLibContentBtn" type="submit" value="Add Method">
              <h3>$error</h3>
            </form>
            </div>
            _LIBINPUT;

        } // end if(!$num)

        else
        {
    
           echo <<<_LIBINPUTDROP
              <div class="formDropDown">
              <button class="formDropDownBtn">Add Content</button>
                <div class="libContentFormContainDrop">
                  <h3>Add a Method for this topic</h3>
                  <form method='post' action='libraries.php?view=$libraryOwner/$libraryName'>
                    <input class="libContentUrl" type='text' name='topicmethodtitle' placeholder="Enter your method"><br>
                    <textarea name='topicmethoddescribe'></textarea><br>
                    <input class="addLibContentBtn" type="submit" value="Add Method">
                    <h3>$error</h3>
                  </form>
                </div>
              </div>
            _LIBINPUTDROP;


          echo "<div class='libraryContent'>"; //library names print container

          for ($j = 0 ; $j < $num ; ++$j)
          {
            $row = $indiLibrary->fetch_array(MYSQLI_ASSOC);
              
            echo "<div class='posts'>" . $row['topicmethodtitle'] . "</div>";
            echo "<div class='posts'>" . $row['topicmethoddescribe'] . "</div>";  // Here the individual content is placed inside a <div> tag
            
          } // end print library names for loop


          echo "</div>"; // End librariesN


        }



        die("</div></body></html>");
      } //end if($view == $viewTest) i.e. individual libraries pages
      else
      {
        /*global $viewPermanent;
        $viewPermanent = $view; */
        echo "<h3>Fail</h3>";
        die("</body></html>");
      } //  end if($view == $viewTest) i.e. individual libraries pages else


      /* End Individual Libraries */

      /* -----------------------------------------------------------------------------------------------------*/
































      /* -----------------------------------------------------------------------------------------------------*/

      /* Start $view != $user a.k.a viewing another user's libraries */


      // Display user being viewed's libraries
      $topicname = array(queryMysql("SELECT topicname FROM topics WHERE auth='$view'"));

      $query  = "SELECT topicname FROM topics WHERE auth='$view'";
      $topicname = queryMysql($query);
      $num    = $topicname->num_rows;

      
      /*$libraryLink = queryMysql("SELECT subliblink FROM libraries WHERE sublibname ='$library'");*/

      echo "<div class='topicN'><h3>$view's topics</h3><ul>"; //library names print container

      for ($j = 0 ; $j < $num ; ++$j)
      {
        $row = $topicname->fetch_array(MYSQLI_ASSOC);
          
        echo "<li><a href='libraries.php?view=" .
          $view . "/" . $row['topicname'] . "'>" . $row['topicname'] . "</a>";

        /*echo "<h3>" . $row['subliblink'] . "</h3>";*/

        
      } // end print library names for loop

       echo "</ul>"; // End print library names list

      echo "</div>"; // End topicN

      if (!$num)
      {
        echo "<div class='noTopics'><h2>This user hasn't contributed to any topics yet</h2></div>";
      } // end if(!$num)




      die("</div></body></html>");


      /* -----------------------------------------------------------------------------------------------------*/








    } //end if($view == $user) else







  } // end isset('view')

?>
