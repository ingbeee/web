    <!--          meta 선언          -->
    <!doctype html>
<html lang="ko">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="asdf/rhead.css" type="text/css">
</head>
    


    <!--          link 선언          -->
    
  
    <title>
    </title>
</head>

<body>
   
<header>
        <div class="header_container">
            <div class="logo_container">
                <a href="index.php">MAIN</a>
            </div>
            <div class="nav_container" id="nav_menu">
                <div class="menu_container">
                    <ul class="menu">
                        
                    <div class="list_container">
                <a href="inform.php">　공지사항　</a>
            </div>
                     
            <div class="list_container">
                <a href="board.php">　자유 게시판　</a>
            </div>
            <div class="list_container">
                <a href="QnA.php">　QnA　</a>
            </div>



            <?php
        $db_id="root";
        $db_pw="duRq3Ou/?ggU";
        $db_name="BOARD";
        $db_domain="localhost";
        
        $conn=mysqli_connect($db_domain,$db_id,$db_pw,$db_name) or die("fail");
            $query ="select * from Board order by number desc";
                $result = $conn->query($query);
                $total = mysqli_num_rows($result);
 
                session_start();
 
                if(isset($_SESSION['userID'])) {
                        
                        
                        echo $_SESSION['userID'];?>
                        <div class="list_container">
                            <p>님 안녕하세요!　</p>
                </div>
                        <div class="list_container">
                            <a href="logout.php">로그아웃</a>
                        </div>

                        <br/>
        <?php
                }
                else {
        ?>             <div class="list_container">
        <a href="login.php">로그인</a>
    </div>
        <?php   }
        ?>

</header>

    
</body>

</html>