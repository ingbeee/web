<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// 댓글 수정을 위해 GET 메서드로 comment_id와 boardID가 제공되었는지 확인합니다.
if (isset($_GET['commentID']) && isset($_GET['boardID'])) {
    $commentID = $_GET['commentID'];
    $boardID = $_GET['boardID'];
   

    // commentID와 boardID 입력값을 유효성 검사하고 정리합니다.
    $commentID = filter_var($commentID, FILTER_VALIDATE_INT);
    $boardID = filter_var($boardID, FILTER_VALIDATE_INT);

    // commentID나 boardID가 유효하지 않은 경우, cm.php 페이지로 리다이렉션합니다.
    if ($commentID === false || $boardID === false) {
        header("Location: cm.php?idx=$boardID");
        exit();
    }

    // 사용자가 댓글 작성자인지 확인합니다.
    $conn = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'BOARD') or die("데이터베이스 연결 실패");
    $query = "SELECT userID, content FROM reply WHERE idx = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $commentID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $commentUserID, $commentContent);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if (!isset($_SESSION['userID']) || $_SESSION['userID'] !== $commentUserID) {
        // 사용자가 댓글 작성자가 아닌 경우, cm.php 페이지로 리다이렉션합니다.
        header("Location: cm.php?idx=$boardID");
        exit();
    }
} else {
    // commentID나 boardID가 제공되지 않은 경우, cm.php 페이지로 리다이렉션합니다.
    header("Location: cm.php?idx=$boardID");
    exit();
}

// 댓글 수정 폼이 제출되었을 때 처리합니다.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    $newContent = $_POST['content'];

    // 새로운 댓글 내용을 유효성 검사합니다. (필요에 따라 추가적인 유효성 검사를 할 수 있습니다.)
    if (empty($newContent)) {
        // 새로운 댓글 내용이 비어있는 경우, 오류 메시지를 표시합니다.
        $errorMessage = "댓글 내용을 입력해주세요.";
    } else {
        // 데이터베이스에서 댓글 내용을 업데이트합니다.
        $conn = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'BOARD') or die("데이터베이스 연결 실패");
        $query = "UPDATE reply SET content = ? WHERE idx = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $newContent, $commentID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // 댓글 수정이 완료된 후, 해당 게시물의 댓글 목록 페이지로 리다이렉션합니다.
        header("Location: cm.php?idx=$boardID");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>댓글 수정</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .error-message {
            color: red;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>댓글 수정</h2>
        <?php if (isset($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <!-- 댓글 수정 폼 -->
        <div class="comment-form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?commentID=' . $commentID . '&boardID=' . $boardID; ?>">
                <textarea name="content" placeholder="댓글을 입력하세요..."><?php echo $commentContent; ?></textarea>
                <br>
                <button type="submit">수정 완료</button>
            </form>
        </div>
        <!-- 댓글 목록으로 돌아가는 버튼 -->
        <form method="get" action="cm.php">
            <input type="hidden" name="number" value="<?php echo $boardID; ?>">
            <button type="submit">댓글 목록으로 돌아가기</button>
        </form>
    </div>
</body>
</html>