<?php
// check.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $userPW = $_POST['userPW'];
    $userName = $_POST['userName'];
    $userEmail = $_POST['userEmail'];
    $verificationCodeInput = $_POST['verificationCode'];

    // 데이터베이스 접속 정보
    $db_id = "root";
    $db_pw = "duRq3Ou/?ggU";
    $db_name = "BOARD";
    $db_domain = "localhost";

    $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
    
    // 입력한 인증 코드와 데이터베이스의 코드 비교
    $sql = "SELECT * FROM CODE WHERE Email = '$userEmail' AND code = '$verificationCodeInput';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if ($row) {
        // 인증 코드가 일치하는 경우, 여기서는 회원 정보를 user 테이블에 추가하지 않고, 인증 코드가 확인되었다는 메시지만 출력합니다.
        echo "인증 코드가 확인되었습니다.";
        
        // 코드 테이블에서 해당 이메일의 인증 코드 삭제
        $sql_delete = "DELETE FROM CODE WHERE Email = '$userEmail'";
        mysqli_query($conn, $sql_delete);
    } else {
        echo "인증 코드가 일치하지 않습니다.";
    }

    // 이전 페이지로 돌아가기
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
?>
