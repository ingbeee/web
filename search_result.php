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

// 데이터베이스 연결 설정
$host = 'localhost';
$username = 'root';
$password = 'duRq3Ou/?ggU';
$databaseUser = 'User'; // User database for "board" table
$databaseBoard = 'BOARD'; // BOARD database for "qna" and "Board" tables

// 데이터베이스 연결
$connectUser = mysqli_connect($host, $username, $password, $databaseUser);
$connectBoard = mysqli_connect($host, $username, $password, $databaseBoard);

// 연결 확인
if (mysqli_connect_errno()) {
    die("데이터베이스 연결에 실패했습니다: " . mysqli_connect_error());
}

function get_view_page_url($source, $number) {
    if ($source === 'board') {
        return "read.php?number={$number}";
    } elseif ($source === 'Board') {
        return "board_read.php?number={$number}";
    } elseif ($source === 'qna') {
        return "q_read.php?number={$number}";
    } 
}

// 사용자가 검색어를 입력한 경우에만 처리
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $searchBy = isset($_GET['catgo']) ? $_GET['catgo'] : 'title'; // 검색 기준이 지정되지 않은 경우 기본적으로 'title'을 사용

    // 입력된 테이블들을 UNION ALL을 사용하여 검색어를 포함하는 게시물을 조회하는 쿼리
    $query = "SELECT number, ID, title, content, date, 'board' AS source FROM {$databaseUser}.board WHERE $searchBy LIKE '%$searchQuery%'
              UNION ALL
              SELECT number, ID, title, content, date, 'Board' AS source FROM {$databaseBoard}.Board WHERE $searchBy LIKE '%$searchQuery%'
              UNION ALL
              SELECT number, ID, title, content, date, 'qna' AS source FROM {$databaseBoard}.qna WHERE $searchBy LIKE '%$searchQuery%' AND secret = 0"; // secret 값이 0인 게시물만 검색

    // 쿼리 실행
    $result = mysqli_query($connectUser, $query);

    // 결과 처리
    if (!$result) {
        die("쿼리 실행에 실패했습니다: " . mysqli_error($connectUser));
    }?>
    <title>검색 결과</title>
<link rel="stylesheet" href="asdf/iinform.css">
</head>
<body>
<div id="board_area"> 
    <!-- 검색어와 검색 조건 표시 -->

    <h4 style="margin-top: 30px;"><a href="/">홈으로</a></h4>
<?php
    // 검색 결과가 있는지 확인
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>검색 결과</h2>";
        ?>
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
        <?php
        echo "<tbody>";

        while ($rows = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td width='50' align='center'>" . $rows['number'] . "</td>";
            echo "<td width='500' align='center'>
            <a href='" . get_view_page_url($rows['source'], $rows['number']) . "'>" . $rows['title'] . "</a>
            </td>";
            echo "<td width='100' align='center'>" . $rows['ID'] . "</td>";
            echo "<td width='200' align='center'>" . $rows['date'] . "</td>";
            echo "<td width='100' align='center'>" . $rows['hit'] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>검색 결과가 없습니다.</p>";
    }

    // 결과를 모두 표시했으면, 결과를 반환하는 메모리를 해제
    mysqli_free_result($result);
}
?>
