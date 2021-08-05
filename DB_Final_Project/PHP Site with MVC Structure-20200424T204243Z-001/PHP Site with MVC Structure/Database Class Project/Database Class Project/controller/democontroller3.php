<?php
set_time_limit(1000);
include_once 'C:\wamp\www\MVC Testing\model\db.php';
//include_once 'db.php';
$result = mysqli_query($conn,"SELECT DISTINCT(primaryTitle) from basics b inner join ratings r on b.tconst = r.tconst inner join episode e on r.tconst=e.parentTconst where b.titleType = 'tvseries' AND e.seasonNumber>1 order by averageRating DESC limit 10;;");

return $result;
?>
