<?php
session_start();

if (!isset($_SESSION['userID'])) {
    // 로그인하지 않은 사용자는 댓글 수정할 수 없으므로 로그인 페이지로 리다이렉트합니다.
    header("Location: login.php");
    exit;
}

// 댓글 ID와 게시물 번호를 받아옵니다.
if (!isset($_POST['replyID']) || !isset($_POST['number'])) {
    echo "잘못된 요청입니다.";
    exit;
}

$replyID = $_POST['replyID'];
$boardID = $_POST['number'];
$userID = $_SESSION['userID'];

// MySQL 데이터베이스 연결 정보
$db_id = "root";
$db_pw = "duRq3Ou/?ggU";
$db_name = "BOARD";
$db_domain = "localhost";

// MySQL 서버에 연결
$conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name) or die("DB 연결 실패");

// 댓글 작성자인지 확인하기 위한 쿼리
$check_query = "SELECT userID FROM reply WHERE replyID = $replyID";
$result = $conn->query($check_query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $writerID = $row['userID'];

    if ($writerID === $userID) {
        // 댓글 작성자인 경우, 댓글 수정 처리
        $content = $_POST['content'];
        $update_query = "UPDATE reply SET content = '$content' WHERE replyID = $replyID";
        if ($conn->query($update_query) === TRUE) {
            // 댓글 수정 성공, 게시물로 리다이렉트
            header("Location: board_read.php?number=$boardID");
            exit;
        } else {
            echo "댓글 수정에 실패했습니다.";
        }
    } else {
        // 댓글 작성자가 아닌 경우, 오류 메시지 출력 후 이전 페이지로 돌아가기
        $message = "본인의 댓글만 수정할 수 있습니다.";
        header("Location: board_read.php?number=$boardID&message=$message");
        exit;
    }
} else {
    // 댓글이 존재하지 않는 경우, 오류 메시지 출력 후 이전 페이지로 돌아가기
    $message = "해당 댓글을 찾을 수 없습니다.";
    header("Location: board_read.php?number=$boardID&message=$message");
    exit;
}
$conn->close();
?>
