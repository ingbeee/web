<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="asdf/wwrite.css" />
</head>
<body>

<header>
<?php include 'rhead.php';?>
                
    </header>

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
        <table1 align = "center">
    <div id="board_write">
            <div id="write_area">
                <form method="post" action="board_wok.php" enctype="multipart/form-data">
                   
                    <div id="in_title">
                        <textarea name="title" id="title" rows="1" cols="55" placeholder="제목" maxlength="100" required></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_name">
                    <input type="text" name="ID" id="ID" placeholder="글쓴이" maxlength="100" value="<?php
                    // 로그인한 사용자의 아이디를 작성자로 표시
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

                    <div class="bt_se">
                        <button type="submit">글 작성</button>
                    </div>
                    </div>
                    </form>
                </form>
            </div>
        </div>
    </body>
                </table1>
                
</html>