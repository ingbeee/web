<?php
session_start(); // Start the PHP session

$wu = 0;
$wp = 0;

// Check if the form has been submitted
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

if (!is_null($userID) && !is_null($userPW) && !is_null($userName) && !is_null($userEmail)) {
    $db_id = "root";
    $db_pw = "duRq3Ou/?ggU";
    $db_name = "User";
    $db_domain = "localhost";

    $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
    $sql = "SELECT userID FROM user WHERE userID = '$userID';";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $userID_e = $row['userID'];
    }
    if ($userID == $userID_e) {
        $wu = 1;
    } else {
        $dbconn = "INSERT INTO user (userID, userPW, userName, userEmail) VALUES ('$userID', '$userPW', '$userName', '$userEmail');";
        mysqli_query($conn, $dbconn);
        echo "<script>alert('가입되었습니다');</script>";
        header("Location: login.php");
        exit;
    }
}

if (isset($_POST['submit'])) {
    $submittedUserID = $_POST['userID'];
    $submittedUserName = $_POST['userName'];
    $submittedUserEmail = $_POST['userEmail'];
}
}
?>


<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>join</title>
    <link rel="stylesheet" href="asdf/rregister.css">
</head>
<body>

<?php include 'rhead.php'; ?>

<div class="login-wrapper">
    <h2>회원가입</h2>
    <form method="POST" action="register.php" id="login-form">
        <input type="text" id="userID" name="userID" placeholder="ID" required value="<?php echo isset($_SESSION['userID']) ? $_SESSION['userID'] : ''; ?>><br>
        <input type="password" id="userPW" name="userPW" placeholder="비밀번호" required value="<?php echo isset($_SESSION['userPW']) ? $_SESSION['userPW'] : ''; ?>><br>
        <input type="text" name="userName" placeholder="닉네임" required  value="<?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : ''; ?>"><br>
        <input type="text" name="userEmail" id="userEmail" placeholder="이메일" required value="<?php echo isset($_SESSION['userEmail']) ? $_SESSION['userEmail'] : ''; ?>><br>

        <button id="Emailbtn" type="button" class="e_btn" onclick="sendEmailVerification()">이메일 인증 코드 받기 </button>
        <input type="hidden" name="emailForVerification" id="emailForVerification" value="">
        <br>

        <div id="verificationCodeInput" style="display: none;">
            <input type="text" name="manualVerificationCode" placeholder="인증 코드 입력" required><br>
            <button type="button" onclick="checkManualVerificationCode()">확인</button>
        </div>
        <br>
        <?php
        if ($wu == 1) {
            echo "<p>사용자이름이 중복되었습니다.</p>";
        }
        if ($wp == 1) {
            echo "<p>비밀번호가 일치하지 않습니다.</p>";
        }
        ?>
        <input type="submit" value="가입하기">
    </form>
</div>

<script>
    function sendEmailVerification() {
        const userEmail = document.getElementById("userEmail").value;
        document.getElementById("emailForVerification").value = userEmail;
        document.getElementById("login-form").action = "mailpost.php";
        document.getElementById("login-form").submit();

        // 이메일 인증 입력란을 표시합니다.
        document.getElementById("verificationCodeInput").style.display = "block";
    }

    function checkManualVerificationCode() {
        const manualVerificationCodeInput = document.getElementById("verificationCodeInput");
        const enteredCode = manualVerificationCodeInput.getElementsByTagName("input")[0].value;
        const generatedCode = "<?php echo $verificationCode; ?>";

        if (enteredCode === generatedCode) {
            // 수동으로 입력한 코드가 생성된 코드와 일치하면, 폼을 제출합니다.
            document.getElementById("login-form").submit();
        } else {
            // 일치하지 않으면, 오류 메시지를 표시합니다.
            alert("인증 코드가 틀렸습니다. 다시 확인해주세요.");
        }
    }
</script>



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
                        alert(data);
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