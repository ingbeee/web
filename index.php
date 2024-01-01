    <!--          meta 선언          -->
    <!doctype html>
<html lang="ko">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <title>main</title>
<link rel="stylesheet" href="asdf/llogin.css" type="text/css">
</head>
    


    <!--          link 선언          -->
    <link rel="stylesheet" href="asdf/head.css">
  
    <title>
        main
    </title>
</head>

<body>
<?php include 'rhead.php';?>
   


        <table1 align = "center">
 
        <div id="search_box">
        <div id="search_box">
    <form action="search_result.php" method="get">
        <select name="catgo">
            <option value="title" <?php if ($catgo === 'title') echo 'selected'; ?>>제목</option>
            <option value="content" <?php if ($catgo === 'content') echo 'selected'; ?>>내용</option>
        </select>
        <input type="text" name="search" size="40" required="required" value="<?php echo $searchKeyword; ?>" />
        <button>검색</button>
    </form>
</div>

</div>

</body>

</html>