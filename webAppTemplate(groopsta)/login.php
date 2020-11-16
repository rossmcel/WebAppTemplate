<?php // Example 26-7: login.php
  require_once 'header.php';
  $error = $user = $pass = "";

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    
    if ($user == "" || $pass == "")
      $error = 'Not all fields were entered';
    else
    {
      $result = queryMySQL("SELECT user,pass FROM members
        WHERE user='$user' AND pass='$pass'");

      if ($result->num_rows == 0)
      {
        $error = "Invalid login attempt";
      }
      else
      {
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;
        $success = TRUE;
        header("Location: index.php");
      }
    }
  }

  echo <<<_END
      <div class="logInForm">
      <form method='post' action='login.php'>
        <div data-role='fieldcontain'>
          <label></label>
          <h2>Please enter your details to log in</h2>
        </div>
        <div data-role='fieldcontain'>
          <label>Username</label>
          <input type='text' maxlength='16' name='user' value='$user'>
        </div>
        <div data-role='fieldcontain'>
          <label>Password</label>
          <input type='password' maxlength='16' name='pass' value='$pass'>
        </div>
        <div data-role='fieldcontain'>
          <label></label>
          <button class="loginBtn" type='submit'>Login</button>
        </div>
        <div data-role='fieldcontain' class="error">
          <label></label>
          <span class='error'>$error</span>
        </div>
      </form>
      </div>
_END;


 if($success)
  {
    echo <<<_REDIRECT
        <div class="goContainer">
          <h3>Welcome $user.<br><br>Please click on the below button to continue</h3>
          <button onclick="window.location.href='members.php?view=$user'" class="loginBtn">Continue</button>
        </div>
        </div>
        </body>
        </html>
        _REDIRECT;
  }


