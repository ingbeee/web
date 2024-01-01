    <!--          meta 선언          -->
    <!doctype html>

<head> <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!--          link 선언          -->
    <link rel="stylesheet" href="asdf/bboard.css" type="text/css">
    
    
    <title>
        게시판
    </title>
</head>

<body>
<?php include 'rhead.php';?>
        
        <p style="font-size:25px; text-align:center"><b>공지</b></p>
        <?php
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

// 정렬 방식에 따라 SQL 쿼리 조건을 설정
if ($sort === '1') {
    $order_by = "ORDER BY date DESC"; // 순번순 정렬
} else if ($sort === '2') {
    $order_by = "ORDER BY hit DESC"; // 조회순 정렬
 // 추천순 정렬
} else {
    // 유효하지 않은 정렬 방식인 경우 기본값으로 최신순 정렬
    $order_by = "ORDER BY date DESC";
}

// 게시글 가져오기 쿼리
$query = "SELECT * FROM board $order_by";
$result = $conn->query($query);
$total = mysqli_num_rows($result);
?>
 <table1 align = "center">
 <form action="inform.php" method="GET">
    <input type="hidden" name="searchh" value="<?= $_GET['search'] ?? '' ?>">
    <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="1" <?php if ($sort === '1') echo 'selected'; ?>>순번순</option>
        <option value="2" <?php if ($sort === '2') echo 'selected'; ?>>조회순</option>
    </select>
</form>
<div id="search_box">
        <form action="is_result.php" method="get">
            <select name="catgo">
                <option value="title" <?php if ($catgo === 'title') echo 'selected'; ?>>제목</option>
                <option value="name" <?php if ($catgo === 'name') echo 'selected'; ?>>글쓴이</option>
                <option value="content" <?php if ($catgo === 'content') echo 'selected'; ?>>내용</option>
            </select>
            <input type="text" name="search" size="40" required="required" value="<?php echo $searchKeyword; ?>" />
            <button>검색</button>
        </form>
    </div>
 

<!-- 정렬 기능 구현 -->

</table1>

    <table align=center>
        <thead align="center">
            <tr>
                <td width="50" align="center">번호</td>
                <td width="500" align="center">제목</td>
                <td width="100" align="center">작성자</td>
                <td width="200" align="center">날짜</td>
                <td width="50" align="center">조회수</td>
               
            </tr>
        </thead>

        <tbody>
            <?php
             while($rows = mysqli_fetch_assoc($result)){ //DB에 저장된 데이터 수 (열 기준)
                if($total%2==0){
                    ?>
                    <tr class="even">
                        <!--배경색 진하게-->
                   <?php 
                } else{
                    ?>
                    <tr>
                        <!--배경색 그냥-->
                   <?php } ?>

                    <td width="50" align="center"><?php echo $total ?></td>
                    <td width="500" align="center">
                        <a href="read.php?number=<?php echo $rows['number'] ?>">
                            <?php echo $rows['title'] ?>
                    </td>
                    
               
            </td> <td width="100" align="center"><?php echo $rows['ID'] ?></td>
                    <td width="200" align="center"><?php echo $rows['date'] ?></td>
                    <td width="50" align="center"><?php echo $rows['hit'] ?></td>
                   
                    </tr>
            <?php
            $total--;
                }
                ?>
        </tbody>
    </table>
          
    
    <div class="bt" id="bt1">
    <?php
    if (isset($_SESSION['userID'])) {
        // 세션에 로그인된 사용자의 정보를 가져옵니다.
        $loggedInUserID = $_SESSION['userID'];

        // User 데이터베이스에서 해당 사용자의 role 값을 가져옵니다.
        $query = "SELECT roll FROM user WHERE userID='$loggedInUserID'";
        $result = $conn->query($query);
        $row = mysqli_fetch_assoc($result);

        // role 값이 "admin"인 경우에만 "글 작성" 버튼을 표시합니다.
        if ($row['roll'] === 'admin') {
            ?>

            
            <table1 align = "center">
            <div class="text">
            <font style="cursor:pointer" onClick="location.href='./write.php'">글 작성</font>
            </table1>
        </div>
            <?php

            
        }
    }
    ?>
</div>
</thead>


                
 
</body>

</html>