<?php
$db_id = "root";
$db_pw = "duRq3Ou/?ggU";
$db_name = "BOARD";
$db_domain = "localhost";

$conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name) or die("데이터베이스 연결 실패");
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

// 검색어와 검색 조건 표시
$searchKeyword = $_GET['search'] ?? '';
$catgo = $_GET['catgo'] ?? 'title';

if ($catgo === 'title') {
    $where = "WHERE title LIKE '%$searchKeyword%'";
} else if ($catgo === 'name') {
    $where = "WHERE ID LIKE '%$searchKeyword%'";
} else if ($catgo === 'content') {
    $where = "WHERE content LIKE '%$searchKeyword%'";
} else {
    $where = '';
}




// 게시글 가져오기 쿼리에 검색 조건 추가
$query = "SELECT * FROM Board $where $order_by";
$result = $conn->query($query);
$total = mysqli_num_rows($result);
?>

<!doctype html>
<head>
<meta charset="UTF-8">
<title>검색 결과</title>
<link rel="stylesheet" href="asdf/iinform.css">
</head>
<body>
<div id="board_area"> 
    <!-- 검색어와 검색 조건 표시 -->
    <h1><?php echo $catgo; ?>에서 '<?php echo $searchKeyword; ?>' 검색결과</h1>
    <h4 style="margin-top: 30px;"><a href="/">홈으로</a></h4>

   

    <!-- 검색 폼 -->
    <div id="search_box">
        <form action="bs_result.php" method="get">
            <select name="catgo">
                <option value="title" <?php if ($catgo === 'title') echo 'selected'; ?>>제목</option>
                <option value="name" <?php if ($catgo === 'name') echo 'selected'; ?>>글쓴이</option>
                <option value="content" <?php if ($catgo === 'content') echo 'selected'; ?>>내용</option>
            </select>
            <input type="text" name="search" size="40" required="required" value="<?php echo $searchKeyword; ?>" />
            <button>검색</button>
        </form>
    </div>

    <!-- 검색 결과 테이블 표시 -->
    <table class="list-table">
        <thead>
            <tr>
                <th width="70">번호</th>
                <th width="500">제목</th>
                <th width="120">글쓴이</th>
                <th width="100">작성일</th>
                <th width="100">조회수</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($rows = mysqli_fetch_assoc($result)) {
                if ($total % 2 == 0) {
                    $tr_class = "even";
                } else {
                    $tr_class = "odd";
                }
                ?>
                <tr class="<?php echo $tr_class; ?>">
                    <td width="70" align="center"><?php echo $total; ?></td>
                    <td width="500" align="center">
                        <a href="board_read.php?number=<?php echo $rows['number']; ?>">
                            <?php echo $rows['title']; ?>
                        </a>
                    </td>
                    <td width="120" align="center"><?php echo $rows['ID']; ?></td>
                    <td width="100" align="center"><?php echo $rows['date']; ?></td>
                    <td width="100" align="center"><?php echo $rows['hit']; ?></td>
                </tr>
            <?php $total--;
            } ?>
        </tbody>
    </table>
</div>
</body>
</html>
