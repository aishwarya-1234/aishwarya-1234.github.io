<?php
set_time_limit(1000);
include_once 'C:\wamp\www\MVC Testing\model\db.php';
//include_once 'db.php';
//result = mysqli_query($conn,"SELECT primaryName from name_basic limit 5;");

   $result = mysqli_query($conn,"SELECT primaryName from name_basic join crew on name_basic.ncost = crew.directors group by directors having count(directors) > 10 limit 5;");
return $result;
?>
