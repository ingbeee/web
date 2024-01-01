<?php
session_start();

// 사용자가 로그인되어 있는지 확인합니다.
if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
    exit();
}

// POST를 통해 comment_id와 boardID가 제공되었는지 확인합니다.
if (isset($_POST['comment_id']) && isset($_POST['boardID'])) {
    $commentID = $_POST['comment_id'];
    $boardID = $_POST['boardID'];
    $writerID = $_POST['writerID'];

    // 데이터베이스 연결
    $conn = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'BOARD') or die("connect fail");

    // 댓글 삭제 쿼리를 준비하고 실행합니다.
    $query = "UPDATE reply SET content = '삭제된 댓글입니다.' WHERE idx = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $commentID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫습니다.
    mysqli_close($conn);

    // 댓글 삭제 후, 해당 게시물 페이지로 리다이렉션합니다. (댓글 목록이 표시된 상태로)
    header("Location: cm.php?idx=$boardID&writerID=$writerID");
    exit();
} else {
    // 만약 comment_id나 boardID가 제공되지 않았다면, 에러 페이지로 리다이렉션하거나 에러를 처리합니다.
   echo "오류: 댓글 ID나 게시물 ID가 제공되지 않았습니다.";
   exit();
}
?>