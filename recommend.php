<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Make sure the user is logged in
    session_start();
    if (!isset($_SESSION['userID'])) {
        die("로그인이 필요합니다.");
    }

    // Get the boardID and userID from the form submission
    $boardID = $_POST['boardID'];
    $userID = $_SESSION['userID'];

    // Database connection information
    $db_id = "root";
    $db_pw = "duRq3Ou/?ggU";
    $db_name = "BOARD";
    $db_domain = "localhost";

    // Connect to the database
    $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name) or die("DB 연결 실패");

    // Check if the user has already recommended this board
    $check_query = "SELECT * FROM rcd WHERE boardID = $boardID AND userID = '$userID'";
    $result = $conn->query($check_query);

    if ($result->num_rows == 0) {
        // The user has not recommended this board yet, so we'll add a new recommendation record
        $insert_query = "INSERT INTO rcd (boardID, userID) VALUES ($boardID, '$userID')";
        if ($conn->query($insert_query)) {
            // 성공적으로 추천 기록을 추가한 경우, 해당 게시물의 추천수를 증가시킵니다.
            $update_thumbup_query = "UPDATE Board SET thumbup = thumbup + 1 WHERE number = $boardID";
            $conn->query($update_thumbup_query);
            
            echo '<script>alert("게시물을 추천했습니다."); history.back();</script>';
        } else {
            echo '<script>alert("추천에 실패했습니다."); history.back();</script>';
        }
    } else {
        // The user has already recommended this board, so we'll remove the recommendation record
        $delete_query = "DELETE FROM rcd WHERE boardID = $boardID AND userID = '$userID'";
        if ($conn->query($delete_query)) {
            // 성공적으로 추천 기록을 삭제한 경우, 해당 게시물의 추천수를 감소시킵니다.
            $update_thumbup_query = "UPDATE Board SET thumbup = thumbup - 1 WHERE number = $boardID";
            $conn->query($update_thumbup_query);

            echo '<script>alert("게시물 추천을 취소했습니다."); history.back();</script>';
        } else {
            echo '<script>alert("추천 취소에 실패했습니다."); history.back();</script>';
        }
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "잘못된 접근입니다.";
}