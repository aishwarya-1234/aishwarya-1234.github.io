<?php
set_time_limit(1000);
include_once "C:\wamp\www\MVC Testing\model\db.php";
include_once "C:\wamp\www\MVC Testing\controller\democontroller.php";


$response = array();

//the array that will hold the titles and links
$posts = array();


while($row=$result->fetch_assoc()) //mysql_fetch_array($sql)
{ 
$primaryName=$row['primaryName']; 


echo "$primaryName";

//each item from the rows go in their respective vars and into the posts array
$posts[] = array('primaryName'=> $primaryName); 

} 

//the posts array goes into the response
$response['posts'] = $posts;

//creates the file
$fp = fopen('results2.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);


?>



 </body>
</html>