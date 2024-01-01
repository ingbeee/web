<?php
session_start();

// Note 데이터베이스에 댓글 정보를 저장하는 함수
function saveComment($boardID, $userID, $content, $file, $link) {
    $conn = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU', 'BOARD') or die("connect fail");
    $query = "INSERT INTO reply1 (boardID, userID, content, date, file, link) VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issss", $boardID, $userID, $content, $file, $link);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// 댓글 작성 폼에서 전송된 데이터 받아오기
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boardID = $_GET['idx']; // 게시물 번호
    $userID = $_SESSION['userID']; // 로그인 세션 아이디를 사용자 ID로 받아옴
    $content = $_POST['content']; // 댓글 내용
    $o_name = $_FILES['b_file']['name'];

    $fileDir = "/upload/";



    if (!empty($_FILES['b_file']['tmp_name']) && is_uploaded_file($_FILES['b_file']['tmp_name'])) {
        $fileName = basename($o_name);
        $targetFile = $fileDir . $fileName;

        //var_dump($targetFile);
       
        // 파일 업로드 수행
        if (!move_uploaded_file($_FILES['b_file']['tmp_name'], $targetFile)) {
            echo "파일 업로드에 실패했습니다.";
            exit;
        }
    } else {
       
        // 파일이 선택되지 않은 경우에는 빈 파일과 파일 경로를 사용
        $fileName = "";
        $targetFile = "";
    }
    
    // 댓글 정보를 Note 데이터베이스의 'reply' 테이블에 저장
    saveComment($boardID, $userID, $content, $fileName, $targetFile);

    // 댓글 작성 후, 해당 게시물 페이지로 리다이렉션 (댓글 목록이 표시된 상태로)
    header("Location: qcm.php?idx=$boardID");
    exit();
}
?>
