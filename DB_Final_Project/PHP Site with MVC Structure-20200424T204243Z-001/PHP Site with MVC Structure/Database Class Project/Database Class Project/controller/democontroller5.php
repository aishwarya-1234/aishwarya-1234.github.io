<?php
set_time_limit(1000);
include_once 'C:\wamp\www\MVC Testing\model\db.php';
//include_once 'db.php';
$result = mysqli_query($conn,"SELECT primaryName,knownForTitles from name_basic group by knownForTitles limit 5;");

return $result;
?>
