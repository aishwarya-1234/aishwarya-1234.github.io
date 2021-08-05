<?php
set_time_limit(1000);
include_once 'C:\wamp\www\MVC Testing\model\db.php';
//include_once 'db.php';
$result = mysqli_query($conn,"select title,region,language,startYear ,averageRating ,numVotes from basics join akas_testing on basics.tconst = akas_testing.titleId join ratings on ratings.tconst = akas_testing.titleId where ratings.averageRating > 7 && ratings.numVotes > 1000 limit 5;");

return $result;
?>
