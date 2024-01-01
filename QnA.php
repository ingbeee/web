
<?php
session_start();
$showWriteLink = true; // 글쓰기 링크를 보일지 여부를 초기화

// 세션에 userID가 존재하는 경우, 즉 로그인된 상태라면
if (isset($_SESSION['userID'])) {
    $session_userID = $_SESSION['userID'];

    // 데이터베이스 연결
    $conn = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'User') or die("connect fail");

    // userID를 이용하여 'user' 테이블에서 해당 사용자의 roll 값을 가져옴
    $query = "SELECT roll FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userRoll);
    mysqli_stmt_fetch($stmt);

    // roll 값이 'admin'인 경우에만 글쓰기 링크를 보이지 않도록 설정
    if ($userRoll === 'admin') {
        $showWriteLink = false;
    } else {
        $showWriteLink = true; // 모든 사용자에게 글쓰기 링크를 보이도록 수정
    }

    // statement와 연결을 닫음
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫음
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
</head>
<style>
        table{
                border-top: 1px solid #444444;
                border-collapse: collapse;
        }
        tr{
                border-bottom: 1px solid #444444;
                padding: 10px;
        }
        td{
                border-bottom: 1px solid #efefef;
                padding: 10px;
        }
        table .even{
                background: #efefef;
        }
        .text{
                text-align:center;
                padding-top:20px;
                color:#000000
        }
        .text:hover{
                text-decoration: underline;
        }
        a:link {color : #57A0EE; text-decoration:none;}
        a:hover { text-decoration : underline;}
</style>
<body>
<?php include 'rhead.php';?>
    <h2 align="center">QnA</h2>
    
 
</form>
<table1 align = "center">

        <td colspan="2" style="text-align: center;">
                <form action="qs_result.php" method="get">
                    <select name="catgo">
                        <option value="title" <?php if ($catgo === 'title') echo 'selected'; ?>>제목</option>
                        <option value="name" <?php if ($catgo === 'name') echo 'selected'; ?>>글쓴이</option>
                        <option value="content" <?php if ($catgo === 'content') echo 'selected'; ?>>내용</option>
                    </select>
                    <input type="text" name="search" size="40" required="required" value="<?php echo $searchKeyword; ?>" />
                    <button>검색</button>
                </form>
                </td>
        </tr>
        </table1>
            <tr>
            <table align="center">
        <thead align="center">
                <td width="50" align="center">번호</td>
                <td width="500" align="center">제목</td>
                <td width="100" align="center">작성자</td>
                <td width="200" align="center">날짜</td>
                <td width="50" align="center">조회수</td>
            </tr>
        </thead>
        <tbody>
        <?php
            // 게시판 데이터 조회
            $connect = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'BOARD') or die("connect fail");
            $query = "select * from qna order by number desc";
            $result = $connect->query($query);
            $total = mysqli_num_rows($result);

            $lockimg = "<img src='https://cdn.pixabay.com/photo/2016/12/18/12/49/cyber-security-1915628_1280.png' alt='lock' title='lock' with='20' height='20' />";
            while ($rows = mysqli_fetch_assoc($result)) {
                // 'secret' 값이 1인 게시물이며, 사용자가 관리자이거나 게시물 작성자인지 확인
                $isSecretPost = $rows['secret'] == 1;
                $isAdmin = $userRoll === 'admin';
                $isPostAuthor = isset($_SESSION['userID']) && $_SESSION['userID'] === $rows['ID'];
                if (!$isSecretPost || $isAdmin || $isPostAuthor) {
                    if ($total % 2 == 0) {
                        echo '<tr class="even">';
                    } else {
                        echo '<tr>';
                    }
            ?>
                <td width="50" align="center"><?php echo $total ?></td>
                <td width="500" align="center">
                    <a href="q_read.php?number=<?php echo $rows['number'] ?>">
                    <?php
                echo $rows['title'];
                if ($isSecretPost) {
                    echo $lockimg;
                }
                ?>
                
                  </a>
                </td>
                <td width="100" align="center"><?php echo $rows['ID'] ?></td>
                <td width="200" align="center"><?php echo $rows['date'] ?></td>
                <td width="50" align="center"><?php echo $rows['hit'] ?></td>
            </tr>
            <?php
                $total--;
            }
        }
            ?>
        </tbody>
    </table>
    <?php if ($showWriteLink) : ?>
        <table1 align = "center">
        <div class="text">
            <font style="cursor: hand" onClick="location.href='./q_write.php'">글쓰기</font>
        </div>
    </table1>
    <?php endif; ?>
</body>
</html>