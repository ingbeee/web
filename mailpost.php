<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userID']) && isset($_POST['userEmail'])) {
    function generateRandomNumber() {
        return mt_rand(100000, 999999);
    }

    $userID = $_POST['userID'];
    $userEmail = $_POST['userEmail']; // 수정된 부분
    $verificationCode = generateRandomNumber();

    // PHPMailer 선언
    $mail = new PHPMailer(true);
    // 디버그 모드(production 환경에서는 주석 처리한다.)
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    // SMTP 서버 세팅
    $mail->isSMTP();
    try {
        // 구글 smtp 설정
        $mail->Host = "smtp.gmail.com";
        // SMTP 암호화 여부
        $mail->SMTPAuth = true;
        // SMTP 포트
        $mail->Port = 465;
        // SMTP 보안 프초트콜
        $mail->SMTPSecure = "ssl";
        // gmail 유저 아이디
        $mail->Username = "ejimail0103@gmail.com";
        // gmail 패스워드
        $mail->Password = "kpcpwisapzbhkdhv";
        // 인코딩 셋
        $mail->CharSet = 'utf-8'; 
        $mail->Encoding = "base64";
        
        // 보내는 사람
        $mail->setFrom('ejimail0103@gmail.com', '은지');
        // 받는 사람
        $mail->AddAddress($userEmail, $userEmail); 
        
        // 본문 html 타입 설정
        $mail->isHTML(true);
        // 제목
        $mail->Subject = '이메일 인증 코드';
        // 본문 (HTML 전용)
        $mail->Body = '<b>이메일 인증 코드</b> Your verification code: ' . $verificationCode;
        // 본문 (non-HTML 전용)
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
        // 메일 전송
        $mail->send();

        $db_id = "root";
        $db_pw = "duRq3Ou/?ggU";
        $db_name = "User";
        $db_domain = "localhost";

        $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
        $code = $verificationCode;
        $sql = "INSERT INTO CODE (userID, userEmail, code) VALUES ('$userID', '$userEmail', '$code')";
        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);

     
            echo "인증 코드가 이메일로 전송되었습니다.";

            exit; 
        } else {
            echo "<p>회원가입 중 오류가 발생했습니다: " . mysqli_error($conn) . "</p>";
        }

        mysqli_close($conn);

    } catch (Exception $e) {
        echo "<script>alert('메일 발송 오류: {$mail->ErrorInfo}');</script>";
    }
}