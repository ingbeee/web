<?php
session_start();

// 사용자가 로그인되어 있는지 확인합니다.
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'User') or die("connect fail");
$query = "SELECT roll FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['userID']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userRoll);
    mysqli_stmt_fetch($stmt);
    
    function alert($msg){
        echo "<script>alert('{$msg}')</script>";
    }
    
    // roll 값이 'admin'인 경우에만 글쓰기 링크를 보이지 않도록 설정
    if ($userRoll === 'admin') {
        $isAdmin = true;
    } else {
        $isAdmin = false;
    }
    
     // statement와 연결을 닫음
     mysqli_stmt_close($stmt);

     // 데이터베이스 연결을 닫음
     mysqli_close($conn);




// POST를 통해 comment_id와 boardID가 제공되었는지 확인합니다.
if (isset($_POST['comment_id']) && isset($_POST['boardID'])) {
    $commentID = $_POST['comment_id'];
    $boardID = $_POST['boardID'];

    // 데이터베이스 연결
    $conn = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'BOARD') or die("connect fail");

    // 댓글 삭제 쿼리를 준비하고 실행합니다.
    $query = "DELETE FROM reply WHERE idx = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $commentID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫습니다.
    mysqli_close($conn);

    // 댓글 삭제 후, 해당 게시물 페이지로 리다이렉션합니다. (댓글 목록이 표시된 상태로)
    header("Location: cm.php?number=$boardID");
    exit();
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>댓글</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .comment-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .comment {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #f0f0f0;
            border-radius: 5px;
        }
        .comment-info {
            font-size: 12px;
            color: #888;
        }
        .comment-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .comment-form textarea {
            width: 100%;
            height: 100px;
            resize: none;
        }
        .comment-form button {
            margin-top: 10px;
        }
        .deleted-comment {
            color: red;
        }
    </style>
</head>
<body>
    <div class="comment-container">
        <h2>댓글 목록</h2>
        <?php
        // 댓글 목록을 가져오는 함수 
        function getComments($boardID) {
            $conn = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'BOARD') or die("데이터베이스 연결 실패");
            $query = "SELECT idx, userID, content, date, boardID AS boardID FROM reply  WHERE boardID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $boardID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
            $comments = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $comments[] = $row;
            }
        
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        
            return $comments;
        }
        
        // URL에서 boardID 가져오기
        $number = $_GET['idx'];
        $writerID = $_GET['writerID'];
        //var_dump($number);
        // 댓글 목록을 가져옴
        $comments = getComments($number);
        

        // 댓글 목록을 표시
        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '<div class="comment-info">';
            echo '작성자: ' . $comment['userID'] . ' | 작성일: ' . $comment['date'];
        
            // 댓글 작성자, 게시물 작성자, 또는 관리자인 경우에만 수정과 삭제 버튼 표시
            if (isset($_SESSION['userID']) && ($_SESSION['userID'] === $comment['userID'] || $_SESSION['userID'] === $writerID || $isAdmin)) {
    // 댓글 작성자와 로그인 사용자가 일치하는 경우에만 수정 버튼 표시
    if ($_SESSION['userID'] === $comment['userID']) {
        if ($comment['content'] !== '삭제된 댓글입니다.') {
            echo '<form style="display: inline-block;" method="get" action="cm_modify.php">';
            echo '<input type="hidden" name="boardID" value="' . $number . '">';
            echo '<input type="hidden" name="commentID" value="' . $comment['idx'] . '">';
            echo '<button type="submit">수정</button>';
            echo '</form>';
        }
    }
   // 댓글이 삭제된 경우 삭제 버튼 표시하지 않음
   if ($comment['content'] !== '삭제된 댓글입니다.') {
    echo '<form style="display: inline-block;" method="post" action="cm_delete.php">';
    echo '<input type="hidden" name="comment_id" value="' . $comment['idx'] . '">';
    echo '<input type="hidden" name="boardID" value="' . $number . '">';
    echo '<input type="hidden" name="writerID" value="' . $writerID . '">';
    echo '<button type="submit">삭제</button>';
    echo '</form>';
}
}
        
            echo '</div>';
            
            // 댓글이 삭제되었는지 확인하고 삭제된 댓글이면 삭제된 메시지를 표시
            if ($comment['content'] === '삭제된 댓글입니다.') {
                echo '<p class="deleted-comment">' . $comment['content'] . '</p>';
            } else {
                echo '<p>' . $comment['content'] . '</p>';
            }
            echo '</div>';
        }
        ?>
    </div>

    <div class="comment-form">
        <h2>댓글 작성</h2>
        <!-- 댓글 작성 폼 -->
        <form method="post" action="cm_write_ok.php?idx=<?php echo $number ?>">
            <textarea name="content" placeholder="댓글을 입력하세요..."></textarea>
            <br>
            <button type="submit">댓글 작성</button>
        </form>
    </div>

    <!-- 버튼으로 만들어진 해당 게시물로 돌아가는 링크 -->
    <form method="get" action="board_read.php">
        <input type="hidden" name="number" value="<?php echo $number ?>">
        <button type="submit">게시물로 돌아가기</button>
    </form>
</body>
</html>