<?php // Example 26-5: signup.php
  require_once 'header.php';

echo <<<_END
  <script>
    function checkUser(user)
    {
      if (user.value == '')
      {
        $('#used').html('&nbsp;')
        return
      }

      $.post
      (
        'checkuser.php',
        { user : user.value },
        function(data)
        {
          $('#used').html(data)
        }
      )
    }
  </script>  
  <div class="signUpFormContain">
_END;

  $error = $user = $pass = "";
  if (isset($_SESSION['user'])) destroySession();

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    $email = sanitizeString($_POST['email']);

    if ($user == "" || $pass == "" || $email == "")
      $error = 'Not all fields were entered<br><br>';
    else
    {
      $result = queryMysql("SELECT * FROM members WHERE user='$user'");
      $result2 = queryMysql("SELECT * FROM members WHERE email='$email'");

      if ($result->num_rows)
      {
        $error = 'That username already exists<br><br>';
      }
      elseif($result2->num_rows)
      {
        $error2 = 'That email has already been regsitered<br><br>';
      }
      else
      {
        queryMysql("INSERT INTO members VALUES('$user', '$pass', '$email')");
        die('<h4>Account created</h4>Please click the below link and log in.</div></body></html>');
        header("Location: index.php");
      }
    }
  }

echo <<<_END
      <form method='post' action='signup.php'>
      <div data-role='fieldcontain'>
        <label></label>
        <h2>Please enter your details to sign up</h2>
      </div>
      <div data-role='fieldcontain'>
        <label>Username</label>
        <input class="used" type='text' maxlength='16' name='user' value='$user'
          onBlur='checkUser(this)'>
        <label></label><div id='used'>&nbsp;</div>
      </div>
      <div data-role='fieldcontain'>
        <label>Password</label>
        <input type='text' maxlength='16' name='pass' value='$pass'>
      </div>
      <div>
        <input type="email" name='email' maxlength='320' placeholder='Email' required>
      </div>
      <div data-role='fieldcontain'>
        <label></label>
        <input class="signUpBtn" data-transition='slide' type='submit' value='Sign Up'>
      </div>
      <h4>$error$error2</h4>
      </form>
    </div>

    </div>
  </body>
</html>
_END;
?>
