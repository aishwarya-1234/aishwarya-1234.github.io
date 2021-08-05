<?php
set_time_limit(1000);
include_once "C:\wamp\www\MVC Testing\model\db.php";
include_once "C:\wamp\www\MVC Testing\controller\democontroller9.php";
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
$titleType=$row['titleType'];
$region=$row['region'];



echo "$titleType";
echo "$region";


//each item from the rows go in their respective vars and into the posts array
$posts[] = array('titleType'=> $titleType,'region'=> $region); 

} 

//the posts array goes into the response
$response['posts'] = $posts;

//creates the file
$fp = fopen('results9.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);


?>

 </body>
</html>