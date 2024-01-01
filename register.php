<?php
session_start();

$wu = 0;
$wp = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $userPW = $_POST['userPW'];
    $userName = $_POST['userName'];
    $userEmail = $_POST['userEmail'];

    // Store form data in the session
    $_SESSION['userID'] = $userID;
    $_SESSION['userPW'] = $userPW;
    $_SESSION['userName'] = $userName;
    $_SESSION['userEmail'] = $userEmail;

    if (!empty($userID) && !empty($userPW) && !empty($userName) && !empty($userEmail)) {
        $db_id = "root";
        $db_pw = "duRq3Ou/?ggU";
        $db_name = "User";
        $db_domain = "localhost";

        $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name)or die("connect fail");
       
        // 사용자 이름 중복 검사
        $sql = "SELECT userID FROM user WHERE userID = '$userID';";
        $result = mysqli_query($conn, $sql);
      

            $verificationCode = rand(100000, 999999);
           
            $sql = "INSERT INTO CODE (userID, userEmail, code) VALUES ('$userID', '$userEmail', '$verificationCode');";
            if (mysqli_query($conn, $sql)) {
                mysqli_close($conn);
                if (isset($_SESSION['userEmail'])) {
                    // echo "<script>alert('인증 코드가 일치하지 않습니다.');</script>";
                }
            } else {
                echo "<p>회원가입 중 오류가 발생했습니다: " . mysqli_error($conn) . "</p>";
            }
        }
    
}
if (isset($_POST['verificationCode']) && !empty($_POST['verificationCode'])) {
    $verificationCode = $_POST['verificationCode'];
    $userID = $_SESSION['userID'];
    $userEmail = $_SESSION['userEmail'];
    $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
    if (!$conn) {
        die("데이터베이스 연결 실패: " . mysqli_connect_error());
    }

    // Check if the verification code matches the one in the database
    $sql = "SELECT * FROM CODE WHERE userID = '$userID' AND userEmail = '$userEmail' AND code = '$verificationCode';";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Verification successful, check for duplicate usernames
        $sql = "SELECT userID FROM user WHERE userID = '$userID';";
        $result_duplicate = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result_duplicate) > 0) {
            $wu = 1;
            echo "<script>alert('아이디가 중복 되었습니다..');</script>";
        } else {
            // Complete user registration
            $userPW = $_SESSION['userPW'];
            $userName = $_SESSION['userName'];
            $sql = "INSERT INTO user (userID, userPW, userName, userEmail) VALUES ('$userID', '$userPW', '$userName', '$userEmail');";
            if (mysqli_query($conn, $sql)) {
                // Delete the used verification code data
                $sql = "DELETE FROM CODE WHERE userID = '$userID' AND userEmail = '$userEmail';";
                mysqli_query($conn, $sql);

                mysqli_close($conn);
                unset($_SESSION['userID']); // Clear session data
                unset($_SESSION['userPW']);
                unset($_SESSION['userName']);
                unset($_SESSION['userEmail']);

                // Show registration success message and redirect to another page
                echo "<script>alert('회원가입이 완료되었습니다.'); window.location.href = 'login.php';</script>";
                exit;
            } else {
                echo "<p>회원가입 중 오류가 발생했습니다: " . mysqli_error($conn) . "</p>";
            }
        }
    } else {
        echo "<script>alert('인증 코드가 일치하지 않습니다.');</script>";
    }
}
?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>회원가입</title>
</head>
<script  src="http://code.jquery.com/jquery-latest.min.js"></script>
<body>

<?php include 'rhead.php';?>

<div class="login-wrapper">
    <h2>회원 가입</h2>
    <form method="POST" id="login-sform">
        <p><input type="text" name="userID" class="userID" placeholder="ID" required value="<?php echo isset($_SESSION['userID']) ? $_SESSION['userID'] : ''; ?>"></p>
        <p><input type="password" name="userPW" placeholder="Password" required value="<?php echo isset($_SESSION['userPW']) ? $_SESSION['userPW'] : ''; ?>"></p>
        <p><input type="text" name="userName" placeholder="Nickname" required value="<?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : ''; ?>"></p>
        <input type="text" name="userEmail" class="userEmail" placeholder="Email" required value="<?php echo isset($_SESSION['userEmail']) ? $_SESSION['userEmail'] : ''; ?>">
        
      
        <p><button id="Emailbtn" class="e_btn" type="button" >이메일 인증</button></p>
        <p><input type="text" name="verificationCode" placeholder="인증 코드 입력"></p>
        <button type="submit">회원가입</button>

    </form>
</div>

</body>
</html>
<script>
    $(()=>{
        $(document).on("click", ".e_btn", function(){
            $.ajax({
                type:"post",
                url : "mailpost.php",
                data : {"userID" : $(".userID").val(), "userEmail" : $(".userEmail").val()},
                success : function(data){
                    if(data){
                        alert("이메일 인증코드를 발송했습니다.");
                    } else {
                        if(msg != ''){
                            alert(msg);
                        }
                        if(move != ''){
                            document.location.href = "../index.php";
                        }
                        
                    }
                }
            })
        })
    })
</script>
