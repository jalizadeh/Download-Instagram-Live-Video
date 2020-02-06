<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="description" content="Free Web tutorials">
	<meta name="keywords" content="HTML,CSS,XML,JavaScript">
	<meta name="author" content="John Doe">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Download Instagram's Live Video / Convert Links</title>
	<style type="text/css">
		textarea {
  			width: 90%;
  			height: 100px;
		}
	</style>
</head>
<body>

<form method="post">
	Paste data here:
	<br/>
	<textarea name="data"><?php if(isset($_POST['data'])) { echo ($_POST['data']); } ?></textarea>
	<br/>
	<input name="submit" type="submit" value="Process Links" />
</form>



<?php


if (!isset($_POST['submit']))
	die();

//get requester ID for further info
$dj = json_decode($_POST['data'], true);
//var_dump($dj);
$customerID = 0;
foreach ($dj as $key => $item) {
    $customerID =  $key;
}

/* 
 * the json contains all the data about all of your followers,
 * so, the data can contain more than one live
$uniqueLivesCount =  sizeof($dj["{$customerID}"]);
echo "broadcasts size: " . $uniqueLivesCount;
echo "<br/>";
*/

foreach ($dj["{$customerID}"] as $value) {
	//echo "inner live: " . sizeof($value["broadcasts"]);
	echo "<br/>";

	$channelUsername = $value["broadcasts"][0]["broadcast_owner"]["username"];
	$channelFullName = $value["broadcasts"][0]["broadcast_owner"]["full_name"];

	echo "<h1>" ;//. $liveCounter;		
	echo ' <a href="https://www.instagram.com/' . $channelUsername . '" target="blank">';
	echo $channelFullName;
	echo '</a>';
	echo "</h1>";

	echo "Following links will expire on: " .	date("Y-m-d H:i:s",$value["broadcasts"][0]["expire_at"]);

	//counter of number broadcast of each unique channel
	$liveCounter = 1;
	foreach ($value["broadcasts"] as $bc) {

		//load the raw xml data, and prepare it
		$dash_manifest = $bc["dash_manifest"];
		$dash_manifest = preg_replace('/&(?!;{6})/', '&amp;', $dash_manifest);
		$dash_manifest = preg_replace('/\\\\/', '', $dash_manifest);
		$xml = simplexml_load_string($dash_manifest);
		
		

		//print video links
		$numberVideos = sizeof($xml->Period->AdaptationSet[0]->Representation);
		echo "<h3>Live #" . $liveCounter . ": ";
		for ($i=0; $i < $numberVideos; $i++) { 
			echo '<a href="';
			echo  $xml->Period->AdaptationSet->Representation[$i]->BaseURL;
			echo '">';
			echo "[🎬 " . $xml->Period->AdaptationSet->Representation[$i]["width"] . "x" .
				$xml->Period->AdaptationSet->Representation[$i]["height"] . "]";
			echo '</a>';
			echo " ";
		}
		//echo "</h3>";

		//print audio link
		echo '<a href="';
		echo  $xml->Period->AdaptationSet[1]->Representation->BaseURL;
		echo '">[🔉 Audio]</a>';
		echo "</h3>";


		$liveCounter++;
	}
}

?>

</body>
</html>