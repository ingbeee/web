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
    $where = "WHERE title LIKE '%$searchKeyword%' AND secret != 1";
} else if ($catgo === 'name') {
    $where = "WHERE ID LIKE '%$searchKeyword%' AND secret != 1";
} else if ($catgo === 'content') {
    $where = "WHERE content LIKE '%$searchKeyword%' AND secret != 1";
} else {
    $where = "WHERE secret != 1";
}


// 정렬 방식에 따라 SQL 쿼리 조건을 설정
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
if ($sort === '1') {
    $order_by = "ORDER BY date DESC"; // 순번순 정렬
} else if ($sort === '2') {
    $order_by = "ORDER BY hit DESC"; // 조회순 정렬
} else {
    // 유효하지 않은 정렬 방식인 경우 기본값으로 최신순 정렬
    $order_by = "ORDER BY date DESC";
}

// 게시글 가져오기 쿼리에 검색 조건 추가
$query = "SELECT * FROM qna $where $order_by";
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
<div id="qna_area"> 
    <!-- 검색어와 검색 조건 표시 -->
    <h1><?php echo $catgo; ?>에서 '<?php echo $searchKeyword; ?>' 검색결과</h1>
    <h4 style="margin-top: 30px;"><a href="/">홈으로</a></h4>

    <!-- 정렬 기능 구현 -->
    <form action="qs_result.php" method="GET">
        <input type="hidden" name="search" value="<?= $_GET['search'] ?? '' ?>">
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="1" <?php if ($sort === '1') echo 'selected'; ?>>순번순</option>
            <option value="2" <?php if ($sort === '2') echo 'selected'; ?>>조회순</option>
        </select>
    </form>

    <!-- 검색 폼 -->
    <div id="search_box">
        <form action="qs_result.php" method="get">
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
                        <a href="q_read.php?number=<?php echo $rows['number']; ?>">
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
