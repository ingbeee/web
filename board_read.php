<style>
.view_table {
border: 1px solid #444444;
margin-top: 30px;
}
.view_title {
height: 30px;
text-align: center;
background-color: #cccccc;
color: white;
width: 1000px;
}
.view_id {
text-align: center;
background-color: #EEEEEE;
width: 30px;
}
.view_id2 {
background-color: white;
width: 60px;
}
.view_hit {
background-color: #EEEEEE;
width: 30px;
text-align: center;
}
.view_hit2 {
background-color: white;
width: 60px;
}
.view_content {
padding-top: 20px;
border-top: 1px solid #444444;
height: 500px;
}
.view_btn {
width: 700px;
height: 200px;
text-align: center;
margin: auto;
margin-top: 50px;
}
.view_btn1 {
height: 50px;
width: 100px;
font-size: 20px;
text-align: center;
background-color: white;
border: 2px solid black;
border-radius: 10px;
}
.view_comment_input {
width: 700px;
height: 500px;
text-align: center;
margin: auto;
}
.view_text3 {
font-weight: bold;
float: left;
margin-left: 20px;
}
.view_com_id {
width: 100px;
}
.view_comment {
width: 500px;
}
</style>

<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>

<!-- board_read.php 페이지의 상단에 다음 PHP 코드를 추가합니다 -->
<?php
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('{$message}');</script>";
}
?>

</head>
<body>
<?php include 'rhead.php';?>
<?php
session_start();
if (!isset($_SESSION['userID'])) {
    ?>
    <script>
        alert("<?php echo "로그인 하세요." ?>");
        location.replace("login.php");
    </script>
    <?php
}
?>

<?php
$db_id = "root";
$db_pw = "duRq3Ou/?ggU";
$db_name = "BOARD";
$db_domain = "localhost";

$conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name) or die("DB 연결 실패");

$number = $_GET['number'];
$hit_update_query = "UPDATE Board SET hit = hit + 1 WHERE number = $number";
$conn->query($hit_update_query);

$query = "SELECT title, content, date, hit, id, thumbup, file, link FROM Board WHERE number = $number";
$result = $conn->query($query);
$rows = mysqli_fetch_assoc($result);
?>

<style>
    .view_table {
        border: 1px solid #444444;
        margin-top: 30px;
    }
    .view_title {
        height: 30px;
        text-align: center;
        background-color: #cccccc;
        color: white;
        width: 1000px;
    }
    .view_id {
        text-align: center;
        background-color: #EEEEEE;
        width: 30px;
    }
    .view_id2 {
        background-color: white;
        width: 60px;
    }
    .view_hit {
        background-color: #EEEEEE;
        width: 40px;
        text-align: center;
    }
    .view_hit2 {
        background-color: white;
        width: 60px;
    }
    .view_content {
        padding-top: 20px;
        border-top: 1px solid #444444;
        height: 500px;
    }
    .view_btn {
        width: 700px;
        height: 200px;
        text-align: center;
        margin: auto;
        margin-top: 50px;
    }
    .view_btn1 {
        height: 50px;
        width: 100px;
        font-size: 20px;
        text-align: center;
        background-color: white;
        border: 2px solid black;
        border-radius: 10px;
    }
    .view_comment_input {
        width: 700px;
        height: 500px;
        text-align: center;
        margin: auto;
    }
    .view_text3 {
        font-weight: bold;
        float: left;
        margin-left: 20px;
    }
    .view_com_id {
        width: 100px;
    }
    .view_comment {
        width: 500px;
    }
</style>


<table class="view_table" align="center">
        <tr>
                <td colspan="4" class="view_title"><?php echo $rows['title']?></td>
        </tr>
        <tr>
                <td class="view_id">작성자</td>
                <td class="view_id2"><?php echo $rows['id']?></td>
        </tr>
        <tr>
                <td class="view_hit">조회수</td>
                <td class="view_hit2"><?php echo $rows['hit']?></td></tr>

                <tr>
        <td class="view_id">추천수</td>
        <td class="view_id2"><?php echo $rows['thumbup'] ?></td>
        <!-- 추천 버튼을 둘러싸는 폼을 추가하여 추천을 제출합니다 -->
    </tr>
 
    <tr><td class="view_id">파일</td>
                <td class="view_id2">
                    <?php if (!empty($rows['file'])): ?>
                    <a href="download.php?board=BOARD&number=<?php echo $number; ?>"><?php echo $rows['file']?></a>
                    <?php else: ?>
                        파일 없음<?php endif; ?></td>
                    </tr>
                    <tr>
                <td colspan="4" class="view_content" valign="top">
                <?php echo $rows['content']?></td>
            </tr>

   
</table>


   <!-- 목록, 수정, 삭제 -->
   <div class="view_btn">
   <button class="view_btn1">
    <a href="board.php">목록</a>
</button>
     

            
            <button class="view_btn1">
            <a href="cm.php?idx=<?php echo $number; ?>&writerID=<?php echo $rows['id']; ?>">댓글</a>
        </button>
    
     
        <?php
         $db_id="root";
         $db_pw="duRq3Ou/?ggU";
         $db_name="User";
         $db_domain="localhost";
 
         $conn=mysqli_connect($db_domain,$db_id,$db_pw,$db_name)or die("fail");
         
        if (isset($_SESSION['userID'])) {
            // 세션에 로그인된 사용자의 정보를 가져옵니다.
            $loggedInUserID = $_SESSION['userID'];
        
            // User 데이터베이스에서 해당 사용자의 role 값을 가져옵니다.
            $query = "SELECT roll FROM user WHERE userID='$loggedInUserID'";
            $result = $conn->query($query);
            $row = mysqli_fetch_assoc($result);
           
            if ($rows['id'] === $loggedInUserID) {
                ?>
               
            <button class="view_btn1">
                <a href="board_modify.php?idx=<?php echo $number; ?>">수정</a>
            </button>
                
                <?php
            }
            
            if ($row['roll'] === 'admin' || ($rows['id'] === $loggedInUserID && $row['roll'] !== 'admin')) {
                // 모든 게시물에 삭제 버튼 보여주는 코드 추가
                ?>
                 <button class="view_btn1">
                <a href="bdelete.php?idx=<?php echo $number; ?>">삭제</a>
                </button>
                <?php
            }
           
        }
        mysqli_close($conn);
        ?>
        <br>

        <form action="recommend.php" method="POST">
        <input type="hidden" name="boardID" value="<?php echo $number; ?>">
        <?php
        // 해당 게시물에 대한 사용자의 추천 기록을 검색합니다.
        $boardID = $_GET['number'];
        $userID = $_SESSION['userID'];

        $db_id = "root";
        $db_pw = "duRq3Ou/?ggU";
        $db_name = "BOARD";
        $db_domain = "localhost";

        $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name) or die("DB 연결 실패");

        $check_query = "SELECT * FROM rcd WHERE boardID = $boardID AND userID = '$userID'";
        $result = $conn->query($check_query);

        if ($result->num_rows == 0) {
            // 추천 기록이 없는 경우, "추천" 버튼을 표시합니다.
            
            echo '<input type="submit" value="추천">';

        } else {
            // 추천 기록이 있는 경우, "추천 취소" 버튼을 표시합니다.
            echo '<input type="submit" value="추천 취소">';
        }

        ?>
       
   </div>
 

        </form>
        

</body>
</html>