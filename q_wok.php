<?php

$db_id="root";
$db_pw="duRq3Ou/?ggU";
$db_name="BOARD";
$db_domain="localhost";

$conn=mysqli_connect($db_domain,$db_id,$db_pw,$db_name) or die("fail");

$ID = $_POST['ID'];
$PW = $_POST['PW'];
$title = $_POST['title'];
$content = $_POST['content'];
$date = date('Y-m-d H:i:s');
$o_name = $_FILES['b_file']['name'];

$fileDir = "/upload/";



// 파일이 선택되었을 때만 파일 업로드 수행
if (!empty($_FILES['b_file']['tmp_name']) && is_uploaded_file($_FILES['b_file']['tmp_name'])) {
    $fileName = basename($o_name);
    $targetFile = $fileDir . $fileName;
   
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


if(isset($_POST['lockpost'])){
	$lo_post = '1';
}else{
	$lo_post = '0';
}

$lo_post = isset($_POST['lockpost']) ? '1' : '0';

$query = "insert into qna (number,title, content, date, hit, ID, PW , secret, file, link) 
        values(null,'$title', '$content', '$date',0, '$ID', '$PW','$lo_post', '$o_name', '$targetFile')";


$result = $conn->query($query);
if($result){
?>                  <script>
        alert("<?php echo "글이 등록되었습니다."?><?php echo $lo_post;?>");
        location.replace("./QnA.php");
    </script>
<?php
}

else{
        echo "FAIL";
}

mysqli_close($conn);
?>
