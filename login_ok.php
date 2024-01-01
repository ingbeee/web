<!doctype html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    <?php
        session_start();
        $db_id="root";
        $db_pw="duRq3Ou/?ggU";
        $db_name="User";
        $db_domain="localhost";
        
        $conn=mysqli_connect($db_domain,$db_id,$db_pw,$db_name);

        if ($conn->connect_error) {
            die("연결실패: " . $conn->connect_error);
        }

        $userID=$_POST['userID'];
        $userPW=$_POST['userPW'];
 
        //아이디가 있는지 검사
        $query = "select * from user where userID='$userID'";
        $result = $conn->query($query);
 
 
        //아이디가 있다면 비밀번호 검사
        if(mysqli_num_rows($result)==1) {
 
                $row=mysqli_fetch_assoc($result);
 
                //비밀번호가 맞다면 세션 생성
                if($row['userPW']==$userPW){
                        $_SESSION['userID']=$userID;
                        if(isset($_SESSION['userID'])){
                        ?>      <script>
                                        alert("로그인 되었습니다.");
                                        location.replace("./index.php");
                                </script>
<?php
                        }
                        else{
                                echo "session fail";
                        }
                }
 
                else {
        ?>              <script>
                                alert("아이디 혹은 비밀번호가 잘못되었습니다.");
                                history.back();
                        </script>
        <?php
                }
 
        }
 
                else{
?>              <script>
                        alert("아이디 혹은 비밀번호가 잘못되었습니다.");
                        history.back();
                </script>
<?php
        }
?>

   
         </body>
     </html>