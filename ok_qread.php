<?php

$db_id = "root";
$db_pw = "duRq3Ou/?ggU";
$db_name = "BOARD";
$db_domain = "localhost";

$conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name) or die("fail");



$bno = $_GET['number']; /* bno함수에 idx값을 받아와 넣음*/
$sql = mq("select * from qna where idx='" . $bno . "'"); /* 받아온 idx값을 선택 */
$board = $sql->fetch_array();

$isAdmin = $userRoll === 'admin';
$isPostAuthor = isset($_SESSION['userID']) && $_SESSION['userID'] === $rows['id'];

header("Location: read.php?idx=" . $board["idx"]);

