<?php
set_time_limit(1000);
include_once "C:\wamp\www\MVC Testing\model\db.php";
include_once "C:\wamp\www\MVC Testing\controller\democontroller4.php";
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
$numVotes=$row['MAX(numVotes)']; 


echo "$numVotes";


//each item from the rows go in their respective vars and into the posts array
$posts[] = array('numVotes'=> $numVotes); 

} 

//the posts array goes into the response
$response['posts'] = $posts;

//creates the file
$fp = fopen('results4.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);


?>
 </body>
</html>