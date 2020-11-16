<?php // Example 26-10: friends.php
  require_once 'header.php';

  if (!$loggedin) die("</div></body></html>");

  if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
  else                      $view = $user;

  echo "<div class='friendsContain'><header><h2>Contacts</h2></header>";



  if ($view == $user)
  {
    $name1 = $name2 = "Your";
    $name3 =          "You are";
  }
  else
  {
    $name1 = "<a href='members.php?view=$view'>$view</a>'s";
    $name2 = "$view's";
    $name3 = "$view is";
  }

  // Uncomment this line if you wish the user’s profile to show here
  // showProfile($view);

  $followers = array();
  $following = array();

  $result = queryMysql("SELECT * FROM friends WHERE user='$view'");
  $num    = $result->num_rows;

  for ($j = 0 ; $j < $num ; ++$j)
  {
    $row           = $result->fetch_array(MYSQLI_ASSOC);
    $followers[$j] = $row['friend'];
  }

  $result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
  $num    = $result->num_rows;

  for ($j = 0 ; $j < $num ; ++$j)
  {
      $row           = $result->fetch_array(MYSQLI_ASSOC);
      $following[$j] = $row['user'];
  }

  $mutual    = array_intersect($followers, $following);
  $followers = array_diff($followers, $mutual);
  $following = array_diff($following, $mutual);
  $friends   = FALSE;

  echo "<br>";
  
  if (sizeof($mutual))
  {
    echo "<div class='myFriends' style='float:left; margin-left: 4%;margin-right: 1%;'>";
    echo "<h3>$name2 mutual friends</h3><ul>";
    foreach($mutual as $friend)
      echo "<li><a data-transition='slide'
            href='members.php?view=$friend'>$friend</a>";
    echo "</ul></div>";
    $friends = TRUE;
  }

  if (sizeof($followers))
  {
    echo "<div class='myFriends' style='float:left;'>";
    echo "<h3 class='subhead'>$name2 followers</h3><ul>";
    foreach($followers as $friend)
      echo "<li><a data-transition='slide'
            href='members.php?view=$friend'>$friend</a>";
    echo "</ul></div>";
    $friends = TRUE;
  }

  /*if (sizeof($following))
  {*/
    echo "<div class='myFriends' style='float:right;margin-right: 4%;margin-left: 1%;'>";
    echo "<h3 class='subhead'>Following</h3><ul>";
    foreach($following as $friend)
      echo "<li><a href='members.php?view=$friend'>$friend</a>";
    echo "</ul></div>";
    $friends = TRUE;
  

  





  /* No friends */
  if (!$friends)
  { 
    echo <<<_NOFRIENDS
        <div class="noFriendsContainer">
          <h2>You haven't added any friends yet, get started below.</h2>
          <!-- Add search bar to search for members -->
          <div class="searchAndResults">
          <form action="friends.php" method="GET">
            <input class="friendNameSearch" type="text" placeholder="Enter a username" name="query" />
            <input class="friendSearchBtn" type="submit" value="Search" />
          </form>
        <div class="searchResults">
        _NOFRIENDS;

        $query = $_GET['query']; 
        // gets value sent over search form
         
        $min_length = 1;
        // you can set minimum length of the query if you want
         
        if(strlen($query) >= $min_length){ // if query length is more or equal minimum length then
             
            $query = sanitizeString($query);   
        
            $raw_results = queryMySQL("SELECT * FROM members
                WHERE (`user` LIKE '%".$query."%')") or die(mysql_error());
                 
            // * means that it selects all fields, you can also write: `id`, `title`, `text`
            // articles is the name of our table
             
            // '%$query%' is what we're looking for, % means anything, for example if $query is Hello
            // it will match "hello", "Hello man", "gogohello", if you want exact match use `title`='$query'
            // or if you want to match just full word so "gogohello" is out use '% $query %' ...OR ... '$query %' ... OR ... '% $query'
             
            if(mysqli_num_rows($raw_results) > 0){ // if one or more rows are returned do following
                 
                while($results = mysqli_fetch_array($raw_results)){
                // $results = mysql_fetch_array($raw_results) puts data from database into array, while it's valid it does the loop
                 
                    echo "<span><a class='resultLink' href='members.php?view=" . $results['user'] . "'>" . $results['user'] . "</a></span><br>";
                    // posts results gotten from database(title and text) you can also show id ($results['id'])
                }
                 
            }
            else{ // if there is no matching rows do following
                echo "No results";
            }
             
        }

        echo "</div></div></div>";
  }
  



   echo <<<_ADDFRIENDS
        <!-- End myFriends div -->
        <div class="addFriendsContainer">
          <h2>Add Other Users</h2>
          <!-- Add search bar to search for members -->
          <div class="searchAndResults2">
          <form action="friends.php" method="GET">
            <input class="friendNameSearch" type="text" placeholder="Enter a username" name="query" />
            <input class="friendSearchBtn" type="submit" value="Search" />
          </form>
        <div class="searchResults">
        _ADDFRIENDS;

        $query = $_GET['query']; 
        // gets value sent over search form
         
        $min_length = 1;
        // you can set minimum length of the query if you want
         
        if(strlen($query) >= $min_length){ // if query length is more or equal minimum length then
             
            $query = sanitizeString($query);   
        
            $raw_results = queryMySQL("SELECT * FROM members
                WHERE (`user` LIKE '%".$query."%')") or die(mysql_error());
                 
            // * means that it selects all fields, you can also write: `id`, `title`, `text`
            // articles is the name of our table
             
            // '%$query%' is what we're looking for, % means anything, for example if $query is Hello
            // it will match "hello", "Hello man", "gogohello", if you want exact match use `title`='$query'
            // or if you want to match just full word so "gogohello" is out use '% $query %' ...OR ... '$query %' ... OR ... '% $query'
             
            if(mysqli_num_rows($raw_results) > 0){ // if one or more rows are returned do following
                 
                while($results = mysqli_fetch_array($raw_results)){
                // $results = mysql_fetch_array($raw_results) puts data from database into array, while it's valid it does the loop
                 
                    echo "<span><a class='resultLink' href='members.php?view=" . $results['user'] . "'>" . $results['user'] . "</a></span><br>";
                    // posts results gotten from database(title and text) you can also show id ($results['id'])
                }
                 
            }
            else{ // if there is no matching rows do following
                echo "No results";
            }
             
        }

        echo "</div></div></div>";



  echo "</div>";
?>
    </div><br>
  </body>
</html>
