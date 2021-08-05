<?php
set_time_limit(1000);
include_once 'C:\wamp\www\MVC Testing\model\db.php';
//include_once 'db.php';
$result = mysqli_query($conn,"SELECT titleType,region from akas_testing join basics on akas_testing.titleId = basics.tconst limit 5;");
return $result;
?>
