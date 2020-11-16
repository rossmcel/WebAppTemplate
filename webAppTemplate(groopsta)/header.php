<?php

  // Start session
  session_start();

  // Determine if user is on the home page - Used to differentiate between non-logged in users on the home page and on normal pages
  if($home)
  {
    $homeHead = TRUE;
  }

  // functions
  require_once 'functions.php';

  // User string (displayed in title)
  $userstr = 'Welcome Guest';

  // Get session status
  if (isset($_SESSION['user']))
  {
    // Get user
    $user     = $_SESSION['user'];
    // logged in status is true
    $loggedin = TRUE;
    // User string (displayed in title)
    $userstr  = "Logged in as: $user";
  } // end if
  else
  {
    // logged in status is false
    $loggedin = FALSE;
  } // end else

  // head section + body opening tag
  echo <<<_INIT
  <!DOCTYPE html> 
  <html>
    <head>
      <!-- main css file -->
      <link rel='stylesheet' href='Assets/CSS/styles.css' type='text/css'>
      <!-- icons -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <!-- meta tags -->
      <meta name="description" content="Health advice from the community">
      <meta name="keywords" content="Health, Advice, Community">
      <!-- javascript file -->
      <script src="Assets/JS/myJavascript.js"></script>
      <!-- JQuery -->
      <script src="Assets/JS/jquery.js"></script>
      <title>groopsta: $userstr</title>
      </head>
      <body>

  _INIT;


  // Display for logged in users
  if($loggedin) 
  {

  echo <<<_LOGGEDINNAV
        <div data-role='header'>
          
        <div class="navbar">
          <div class="navdropdown">
            <button class="navdropbtn"><i class='material-icons'>menu</i></button>
            <div class="navdropdown-content">
                <i class='material-icons'><a href="topics.php">library_books</a></i>
                <i class='material-icons'><a href="members.php?view=$user">account_circle</a></i>
                <i class='material-icons'><a href="friends.php">bubble_chart</a></i>
                <i class='material-icons'><a href="messages.php">chat</a></i>
                <i class='material-icons'><a href="logout.php">logout</a></i>
            </div><!-- End navdropdown-content -->
          </div><!-- End navdropdown -->
          <a class="logo" href="index.php">groopsta<!-- <img src="Assets/Images/logo3.jpg"> --></a>
          <form method="GET" class='navbarSearch'>
              <input class="navbarSearchText" type='text' name='query' placeholder="Enter a topic to search for">
              <input class="navbarSearchBtn" type="submit" value="Search">
              <h3>$error</h3>
          </form> 
          <!-- <div class="signUp">
            <i class='material-icons'><a href="topics.php">library_books</a></i>
            <i class='material-icons'><a href="members.php?view=$user">account_circle</a></i>
            <i class='material-icons'><a href="friends.php">bubble_chart</a></i>
            <i class='material-icons'><a href="messages.php">chat</a></i>
            <i class='material-icons'><a href="logout.php">logout</a></i>
          <!-- </div>--> <!-- End signUp -->
          </div> <!-- End navbar -->
          </div> <!-- End data-role='header' -->
  _LOGGEDINNAV;

  }

  // Display for non-logged in users not on the home page
  if(!$loggedin && !$homeHead)
  {
    echo <<<_LOGGEDINNAV
        <div class="navbar">
          <a class="logo" href="index.php">groopsta<!-- <img src="Assets/Images/logo3.jpg"> --></a>
          <div class="signUp">
            <i class='material-icons'><a href="topics.php">library_books</a></i>
            <!-- <i class='material-icons'><a href="members.php?view=$user">account_circle</a></i> -->
            <!-- <i class='material-icons'><a href="friends.php">bubble_chart</a></i> -->
            <!-- <i class='material-icons'><a href="messages.php">chat</a></i> -->
            <i class='material-icons'><a href="logout.php">logout</a></i>
          </div> <!-- End signUp -->
          </div> <!-- End navbar -->
  _LOGGEDINNAV;
  }

?>
