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

$pattern_only_links = '/<MPD.*?<\/MPD>/';
$pattern_link = '~[a-z]+://[a-zA-Z0-9\.\/_?=&;-]+~';
$data = $_POST['data'];

/*
//get requester ID for further info
$dj = json_decode($data, true);
//var_dump($dj);
$customerID = 0;
foreach ($dj as $key => $item) {
    $customerID =  $key;
}
*/

//$channelUsername = $dj["{$customerID}"][0]["broadcasts"][0]["broadcast_owner"]["username"];
//$channelFullName = $dj["{$customerID}"][0]["broadcasts"][0]["broadcast_owner"]["full_name"];


//keep only the dash_manifest data
preg_match_all($pattern_only_links, $data, $data);




$liveCounter = 1;
foreach ($data[0] as $value) {
	$value = preg_replace('/&(?!;{6})/', '&amp;', $value);
	$value = preg_replace('/\\\\/', '', $value);
	$xml = simplexml_load_string($value);
	

	echo "<h1>Live #" . $liveCounter;
	/*
	echo ' <a href="https://www.instagram.com/' . $channelUsername . '" target="blank">';
	echo $channelFullName;
	echo '</a>';
	*/
	echo "</h1>";


	//print video links
	$numberVideos = sizeof($xml->Period->AdaptationSet[0]->Representation);
	echo "<h3>";
	//echo "There are " . $numberVideos . " qualities <br/>";
	echo "Video:";
	for ($i=0; $i < $numberVideos; $i++) { 
		echo '<a href="';
		echo  $xml->Period->AdaptationSet->Representation[$i]->BaseURL;
		echo '">';
		echo "[" . $xml->Period->AdaptationSet->Representation[$i]["width"] . "x" .
			$xml->Period->AdaptationSet->Representation[$i]["height"] . "]";
		echo '</a>';
		echo " ";
	}
	echo "</h3>";

	//print audio link
	echo "<h3>Audio:";
	echo '<a href="';
	echo  $xml->Period->AdaptationSet[1]->Representation->BaseURL;
	echo '">[Download]</a>';
	echo "</h3>";



	//var_dump($xml->Period->AdaptationSet->Representation);
	//var_dump($xml);
	echo "<br/><br/>";
	$liveCounter++;
}



/*
if($data != ''){
	$num_found = preg_match_all($pattern_link, $data, $out);
	
	echo 'I found: ' .  $num_found . ' links.'; 
	print_r($out[0]);

	if($num_found > 0){
		preg_replace('/&amp;/', '&', $out[0]);

		echo "<h1>Live #1:</h1>";
		echo '	<h3>
				Video: 
				<a href="' .  $out[0][1] . '">[216x418]</a> 
				<a href="' .  $out[0][4] . '">[324x628]</a> 
				<a href="' .  $out[0][3] . '">[396x768]</a>
				<a href="' .  $out[0][2] . '">[504x976]</a>
				</h3>';
		echo '<h3>Audio: <a href="' .  $out[0][5] . '">[Download]</a></h3>';

		if($num_found > 8){
			echo "<h1>Live #2:</h1>";
			echo '	<h3>
					Video: 
					<a href="' .  $out[0][9]  . '">[216x418]</a> 
					<a href="' .  $out[0][12] . '">[324x628]</a> 
					<a href="' .  $out[0][11]  . '">[396x768]</a>
					<a href="' .  $out[0][10]  . '">[504x976]</a>
					</h3>';
			echo '<h3>Audio: <a href="' .  $out[0][13] . '">[Download]</a></h3>';
		}

	} else {
		echo '<h3>Error: No link found. Please make sure you copied the right data.</h3>';
	}

} else {
	echo '<h3>Error: There is no data to process.</h3>';
}

*/
?>

</body>
</html>