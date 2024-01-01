<?php

$db_id="root";
$db_pw="8963";
$db_name="duRq3Ou/?ggU";
$db_domain="localhost";

$db=mysqli_connect($db_domain,$db_id,$db_pw,$db_name) or die("error");

if($db){
    echo "ok";
}
else{
    echo "no";
}
?>