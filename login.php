<!doctype html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <title>login</title>
<link rel="stylesheet" href="asdf/llllogin.css" type="text/css">
</head>
<body>

<?php include 'rhead.php';?>
        
    <div class="login-wrapper">
    <?php
      if ( $jb_login ) {
    ?>
      <h1>이미 로그인하셨습니다.</h1>
    <?php
      } else {
    ?>
        <h2>Login</h2>
        <form method="post" name="login_form"  action="login_ok.php" id="login-form">
            <input type="text" name="userID" id="userID" class="userID" placeholder="ID"><br>
            <input type="password" name="userPW" id="userPW" class="userPW" placeholder="Password">
            <br>
            <a href ="register.php">회원가입</a><br>
            <input type="submit" value="Login">
         
        </form>
        <?php
      } 
      ?>


        <footer>
        <div class="footer_container">
            <div class="footA">
                eunji's
            </div>
            <div class="footB">
                Copyright © eunji's All Rights Reserved.
            </div>
        </div>
    </footer>
    </div>
</body>