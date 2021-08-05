<?php
set_time_limit(1000);
include_once 'C:\wamp\www\MVC Testing\model\db.php';
//include_once 'db.php';
$result = mysqli_query($conn,"SELECT * from name_basic where ncost IN(select ncost from name_basic where primaryProfession = 'actress') limit 5;");


return $result;
?>
