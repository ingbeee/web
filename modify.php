<?php
session_start();

$connect = mysqli_connect("localhost", "root", "duRq3Ou/?ggU", "User") or die("connect fail");

$number = $_GET['idx'];
$query = "SELECT title, content, date, ID FROM board WHERE number=$number";
$result = $connect->query($query);
$rows = mysqli_fetch_assoc($result);

$title = $rows['title'];
$content = $rows['content'];
$userid = $rows['ID'];

$URL = "/inform.php";

?>


<form method="post" action="modify_action.php">
    <table style="padding-top: 50px" align="center" width="700" border="0" cellpadding="2">
        <tr>
            <td height="20" align="center" bgcolor="#ccc"><font color="white"> 글수정 <?= $number ?> </font></td>
        </tr>
        <tr>
            <td bgcolor="white">
                <table class="table2">
                    <tr>
                        <td>작성자
               </td>
               <td><input type="text" name="ID" size="20" value="<?php
                            // 로그인한 사용자의 아이디를 작성자로 표시
                            if (isset($_SESSION['userID'])) {
                                echo $_SESSION['userID'];
                            } else {
                                echo ""; // 사용자가 로그인하지 않은 경우 빈칸으로 표시됩니다.
                            }
                            ?>" readonly></td>
                        
                    </tr>

                    <tr>
                        <td>제목</td>
                        <td><input type="text" name="title" size="60" value="<?= $title ?>"></td>
                    </tr>

                    <tr>
                        <td>내용</td>
                        <td><textarea name="content" cols="85" rows="15"><?= $content ?></textarea></td>
                    </tr>
                </table>

                <center>
                    <input type="hidden" name="number" value="<?= $number ?>">
                    <input type="submit" value="수정하기">
                </center>
            </td>
        </tr>
    </table>
</form>