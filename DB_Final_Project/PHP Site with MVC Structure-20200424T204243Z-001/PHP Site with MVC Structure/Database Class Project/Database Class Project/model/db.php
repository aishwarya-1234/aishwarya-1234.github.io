<?php
set_time_limit(1000);
$url='chirag.c0bkhf7d1mmz.us-east-1.rds.amazonaws.com';
$username='admin';
$password='password';
$conn=mysqli_connect($url,$username,$password,"chirag");
if(!$conn){
 die('Could not Connect My Sql:' .mysql_error());
}
?>