<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="asdf/wwrite.css" />
</head>
<body>

<?php include 'rhead.php';?>

    <?php
        session_start();
        if(!isset($_SESSION['userID']))
        {
                ?>
                <script>
        alert("<?php echo "로그인 하세요."?>");
        location.replace("login.php");
    </script>
        <?php }?>

        <?php


if (isset($_POST['title']) && isset($_POST['content'])) {
  // ... (폼 처리 및 글을 데이터베이스에 삽입하는 기존 코드)

  // 글을 데이터베이스에 삽입한 후, 비밀글 여부에 따라 'secret' 필드를 업데이트하는 코드를 추가합니다.
  $secret_post = isset($_POST['secret_post']) && $_POST['secret_post'] === 'on' ? 1 : 0;
  $insert_query = "INSERT INTO qna (title, content, date, hit, ID, secret, file, link) VALUES ('$title', '$content', NOW(), 0, '$id', '$secret_post','$o_name', '$targetFile')";
  // ... (삽입 쿼리를 실행)
}
?>
<table1 align = "center">
    <div id="board_write">
            <div id="write_area">
                <form method="post" action="q_wok.php" enctype="multipart/form-data">
                  
                    <div id="in_title">
                        <textarea name="title" id="title" rows="1" cols="55" placeholder="제목" maxlength="100" required></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_name">
                    <input type="text" name="ID" id="ID" placeholder="글쓴이" maxlength="100" value="<?php
                    // 로그인한 아이디를 작성자
                    if (isset($_SESSION['userID'])) {
                        echo $_SESSION['userID'];
                    }
                ?>" readonly required>
                    </div>
                    <div class="wi_line"></div>
                    
                    
                    <div id="in_content">
                        <textarea name="content" id="content" rows="30" cols="55" placeholder="내용" required></textarea>
                    </div>
  
                    <tr>
                        <td> 파일 </td>
                        <td><input type = "file" name = "b_file" /></td>
                        </tr>
                        </table>

                        <div id="in_lock">
                        <input type="checkbox" value="1" id="lockpost" name="lockpost" />해당글을 잠급니다.
                    </div>

                        </table>
                    <div class="bt_se">
                        <button type="submit">글 작성</button>
                    </div>
                    
                    </div>
                  
                </form>
            </div>
        </div>
</table1>
    </body>
</html>