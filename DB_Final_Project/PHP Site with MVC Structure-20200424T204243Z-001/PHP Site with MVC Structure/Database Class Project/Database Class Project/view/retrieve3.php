<?php
set_time_limit(1000);
include_once "C:\wamp\www\MVC Testing\model\db.php";
include_once "C:\wamp\www\MVC Testing\controller\democontroller3.php";
?>
<?php
$response = array();

//the array that will hold the titles and links
$posts = array();


while($row=$result->fetch_assoc()) //mysql_fetch_array($sql)
{ 
$primaryTitle=$row['primaryTitle']; 


echo "$primaryTitle";

//each item from the rows go in their respective vars and into the posts array
$posts[] = array('primaryTitle'=> $primaryTitle); 

} 

//the posts array goes into the response
$response['posts'] = $posts;

//creates the file
$fp = fopen('results3.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);


?>



 </body>
</html>