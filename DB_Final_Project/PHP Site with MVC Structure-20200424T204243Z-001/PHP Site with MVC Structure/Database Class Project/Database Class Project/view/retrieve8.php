<?php
set_time_limit(1000);
include_once "C:\wamp\www\MVC Testing\model\db.php";
include_once "C:\wamp\www\MVC Testing\controller\democontroller8.php";
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
$ncost=$row['ncost'];
$primaryName=$row['primaryName'];
$birthYear=$row['birthYear'];
$deathYear=$row['deathYear'];
$primaryProfession=$row['primaryProfession'];
$knownForTitles=$row['knownForTitles'];



echo "$ncost";
echo "$primaryName";
echo "$birthYear";
echo "$deathYear";
echo "$primaryProfession";
echo "$knownForTitles";


//each item from the rows go in their respective vars and into the posts array
$posts[] = array('ncost'=> $ncost,'primaryName'=> $primaryName,'birthYear'=> $birthYear,'deathYear'=> $deathYear,'primaryProfession'=> $primaryProfession,'knownForTitles'=> $knownForTitles); 

} 

//the posts array goes into the response
$response['posts'] = $posts;

//creates the file
$fp = fopen('results8.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);


?>

 </body>
</html>