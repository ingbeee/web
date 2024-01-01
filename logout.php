<!doctype html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    <?php
       session_start();
       session_destroy();
    ?>
      <script>
    alert("로그아웃 되었습니다");
    location.replace('index.php');
   
   </script>     
  </body>
</html>