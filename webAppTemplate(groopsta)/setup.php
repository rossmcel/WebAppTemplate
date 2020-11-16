<!DOCTYPE html>
<html>
  <head>
    <title>Setting up database</title>
  </head>
  <body>

    <h3>Setting up...</h3>

<?php // Example 26-3: setup.php
  require_once 'functions.php';

  createTable('members',
              'user VARCHAR(16),
              email VARCHAR(320),
              pass VARCHAR(16),
              INDEX(user(6))');

  createTable('messages', 
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              auth VARCHAR(16),
              recip VARCHAR(16),
              time INT UNSIGNED,
              message VARCHAR(4096),
              viewed VARCHAR(16),
              INDEX(auth(6)),
              INDEX(recip(6))');

  createTable('friends',
              'user VARCHAR(16),
              friend VARCHAR(16),
              INDEX(user(6)),
              INDEX(friend(6))');

  createTable('profiles',
              'user VARCHAR(16),
              text VARCHAR(4096),
              INDEX(user(6))');

  createTable('topics', 
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              auth VARCHAR(16),
              topicname VARCHAR(100),
              topicmethodtitle VARCHAR(100),
              topicmethoddescribe VARCHAR(4096),
              time INT UNSIGNED,
              viewed VARCHAR(100),
              INDEX(auth(6)),
              INDEX(topicname(6)),
              INDEX(viewed(6))'); 
?>

    <br>...done.
  </body>
</html>
