<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userID']) && isset($_POST['userEmail'])) {
    $userID = $_POST['userID'];
    $userEmail = $_POST['userEmail'];

    // 랜덤으로 6자리 숫자 생성
    $verificationCode = rand(100000, 999999);

    // PHPMailer 객체를 생성합니다.
    $mail = new PHPMailer(true);

    try {
        // 메일 서버 설정
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ggh91182@gmail.com'; // 발신자 이메일 주소
        $mail->Password = 'apkiwpktgbkmrtxp'; // 발신자 이메일 비밀번호
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // 보내는 사람 설정
        $mail->setFrom('ggh91182@gmail.com', 'KGH');

        // 이메일 내용 설정
        $mail->isHTML(true);
        $mail->Subject = 'KGH 이메일 인증';
        $mail->Body = "{$verificationCode}";

        // 받는 사람 설정 (회원가입에 사용한 userEmail 값을 사용)
        $mail->addAddress($userEmail, $userID);

        // 메일 보내기
        $mail->send();

        // 데이터베이스에 입력된 정보 저장
        $db_id = "root";
        $db_pw = "lX#%qSYKw8jn";
        $db_name = "member";
        $db_domain = "localhost";

        $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
        if (!$conn) {
            die("데이터베이스 연결 실패: " . mysqli_connect_error());
        }

        // Save the verification data in the user_verification table
        $sql = "INSERT INTO user_verification (userID, userEmail, verificationCode) VALUES ('$userID', '$userEmail', '$verificationCode');";
        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);

            // 확인 버튼을 누르면 Membership.php로 이동하기 위한 JavaScript 리다이렉트 코드
            echo "인증 코드가 이메일로 전송되었습니다.";
            exit; // 리다이렉트 후 스크립트를 중단하여 뒤에 코드가 실행되지 않도록 합니다.
        } else {
            echo "<p>회원가입 중 오류가 발생했습니다: " . mysqli_error($conn) . "</p>";
        }

        mysqli_close($conn);

    } catch (Exception $e) {
        echo "<script>alert('메일 발송 오류: {$mail->ErrorInfo}');</script>";
    }
}
?>