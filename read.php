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
</head>
<body>

<?php include 'rhead.php';?>
	
    <?php
        $db_id="root";
        $db_pw="duRq3Ou/?ggU";
        $db_name="User";
        $db_domain="localhost";

        $conn=mysqli_connect($db_domain,$db_id,$db_pw,$db_name)or die("fail");
        
        
        $number = $_GET['number'];
        $hit_update_query = "UPDATE board SET hit = hit + 1 WHERE number = $number";
        $conn->query($hit_update_query);
      
        $query = "select title, content, date, hit, id, file, link from board where number =$number";
        $result = $conn->query($query);
        $rows = mysqli_fetch_assoc($result);
	?>
<!-- 글 불러오기 -->

<table class="view_table" align=center>
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
                </tr>
                <tr><td class="view_id">파일</td>
                <td class="view_id2">
                    <?php if (!empty($rows['file'])): ?>
                    <a href="download.php?board=User&number=<?php echo $number; ?>"><?php echo $rows['file']?></a>
                    <?php else: ?>
                        파일 없음<?php endif; ?></td>
                    </tr>

                    <tr>
                <td colspan="4" class="view_content" valign="top">
                <?php echo $rows['content']?></td>
            </tr>

            </tr>
        </table>
	<!-- 목록, 수정, 삭제 -->
    <div class="view_btn">
    <button class="view_btn1">
        <a href="inform.php">목록</a></button>
   
        <?php
        if (isset($_SESSION['userID'])) {
            // 세션에 로그인된 사용자의 정보를 가져옵니다.
            $loggedInUserID = $_SESSION['userID'];
    
            // User 데이터베이스에서 해당 사용자의 role 값을 가져옵니다.
            $query = "SELECT roll FROM user WHERE userID='$loggedInUserID'";
            $result = $conn->query($query);
            $row = mysqli_fetch_assoc($result);
            if ($row['roll'] === 'admin') {
                ?>
                <button class="view_btn1">
			<a href="modify.php?idx=<?php echo $number; ?>">수정</a>
            </button>
            <button class="view_btn1">
			<a href="delete.php?idx=<?php echo $number; ?>">삭제</a>
            </button>
            <?php
        }
    }
    ?>
     

	</div>
</div>

</body>
</html>