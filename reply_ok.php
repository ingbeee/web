<?php
session_start();
if (!isset($_SESSION['userID'])) {
    // 로그인하지 않은 경우, 로그인 페이지로 리다이렉트
    header("Location: login.php");
    exit;
}

// POST 요청으로 넘어온 댓글 정보 가져오기
if (!isset($_GET['number']) || !isset($_POST['content'])) {
    // 댓글 정보가 제대로 전달되지 않은 경우, 오류 메시지 출력 후 이전 페이지로 돌아가기
    echo "잘못된 요청입니다.";
    header("Location: view.php?number=" . $_GET['number']);
    exit;
}

$boardID = $_GET['number'];
$userID = $_SESSION['userID'];
$content = $_POST['content'];

// MySQL 데이터베이스 연결 정보
$db_id = "root";
$db_pw = "duRq3Ou/?ggU";
$db_name = "BOARD";
$db_domain = "localhost";

// MySQL 서버에 연결
$conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name) or die("DB 연결 실패");

// 현재 시간 가져오기
$date = date('Y-m-d H:i:s');

// 댓글 데이터를 데이터베이스에 삽입하기 위한 쿼리 준비
$query = "INSERT INTO reply (boardID, userID, content, date) VALUES ('$boardID', '$userID', '$content', '$date')";

// 쿼리 실행
if ($conn->query($query) === TRUE) {
 
   
    header("Location: board_read.php?number=" . $_GET['number'] . "&message=등록되었습니다.");
} else {
    // 댓글 작성 실패 시, 오류 메시지 출력
    echo '<script>alert("등록 실패"); history.back();</script>';
}

// MySQL 연결 종료
$conn->close();
?>
