<?php
set_time_limit(1000);
include_once "C:\wamp\www\MVC Testing\model\db.php";
include_once "C:\wamp\www\MVC Testing\controller\democontroller2.php";
?>
<!DOCTYPE html>
<html>
 <head>
 <title> Retrive data</title>
 </head>
<body>

<?php
$response = array();

//the array that will hold the titles and links
$posts = array();


while($row=$result->fetch_assoc()) //mysql_fetch_array($sql)
{ 
$title=$row['title']; 
$region=$row['region'];
$language=$row['language']; 
$startYear=$row['startYear']; 
$averageRating=$row['averageRating'];
$numVotes=$row['numVotes']; 

echo "$title";
echo "$region";
echo "$language";
echo "$startYear";
echo "$averageRating";
echo "$numVotes";

//each item from the rows go in their respective vars and into the posts array
$posts[] = array('title'=> $title, 'region'=> $region , 'language'=> $language,'startYear'=> $startYear, 'averageRating'=> $averageRating , 'numVotes'=> $numVotes); 

} 

//the posts array goes into the response
$response['posts'] = $posts;

//creates the file
$fp = fopen('results1.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);


?>
 </body>
</html>