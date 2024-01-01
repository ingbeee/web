<?php
// delete.php


$connect = mysqli_connect("localhost", "root", "duRq3Ou/?ggU", "User") or die("connect fail");

$number = $_GET['idx'];

// 해당 글의 작성자 정보를 가져옵니다.
$query = "SELECT id FROM board WHERE number = $number";
$result = $connect->query($query);
$row = mysqli_fetch_assoc($result);
$writerID = $row['id'];


// 게시물을 삭제합니다.
$delete_query = "DELETE FROM board WHERE number = $number";
$delete_result = $connect->query($delete_query);

if ($delete_result) {
    // 삭제 성공시 게시판 목록으로 이동합니다.
    header("Location: inform.php");
    exit();
} else {
    // 삭제 실패시 오류 메시지를 출력합니다.
    echo "게시물 삭제에 실패했습니다.";
}
?>