<?php 
    $board = $_GET['board'];  // 게시글 종류
    $number = $_GET['number']; // 게시글 번호
    $name = "";
    $path = "";
    $query = "";
    $connect = mysqli_connect('localhost', 'root', 'duRq3Ou/?ggU');
    if ($board === 'User') { 
        $query = "SELECT file, link FROM User.board WHERE number = $number";
        $result = $connect->query($query);

        $row = mysqli_fetch_assoc($result);
        var_dump($row);
        $path = $row['link'];
        $name = $row['file'];
    } elseif ($board === 'BOARD') { 
        $query = "SELECT file, link FROM BOARD.Board WHERE number = $number";
        $result = $connect->query($query);
        $row = mysqli_fetch_assoc($result);
        var_dump($row);
        $path = $row['link'];
        $name = $row['file'];
    } elseif ($board === 'qna') { 
        $query = "SELECT file, link FROM BOARD.qna WHERE number = $number";
        $result = $connect->query($query);
        $row = mysqli_fetch_assoc($result);
        var_dump($query, $result, $row);
        $path = $row['link'];
        $name = $row['file'];
    } elseif ($board === 'qcm') { 
        $query = "SELECT file, link FROM BOARD.reply1 WHERE idx = $number";
        $result = $connect->query($query);
        $row = mysqli_fetch_assoc($result);
        var_dump($query, $result, $row);
        $path = $row['link'];
        $name = $row['file'];
    }
    else {
        die("잘못된 게시판 유형");
    }

    

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $name);
    header("Content-Length: " . filesize($path));
    header('Content-Transfer-Encoding: binary');
    ob_clean();
    flush();
    readfile($path);
    exit;
?>
