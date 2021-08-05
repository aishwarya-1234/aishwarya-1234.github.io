<?php
set_time_limit(1000);
include_once 'C:\wamp\www\MVC Testing\model\db.php';
//include_once 'db.php';
$result = mysqli_query($conn,"SELECT MAX(numVotes) from ratings;");

return $result;
?>
