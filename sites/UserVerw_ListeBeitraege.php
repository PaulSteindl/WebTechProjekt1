<?php
//gibt von usern alle beiträge aus
if (isset($_SESSION['rolle'])) {
	if ($_SESSION['rolle'] == 0) {
		$abfrage = $conn->getHistory($_GET['history']);
		
	} else {
		$error = 'notEnoughRights';
		header("Location: ./index.php?page=home&error=$error&rolle=$rolle");
		exit();
	}
} else {
	$error = 'cannotFindRole';
	header("Location: ./index.php?page=home&error=$error");
	exit();
}
if($abfrage != NULL){
	foreach($abfrage as $ausgabe){
		echo '
			<br>
			<b>pid: </b> '.$ausgabe->pid.'<br>
			<b>uid: </b> '.$ausgabe->uid.'<br>
			<b>bid: </b> '.$ausgabe->bid.'<br>
			<b>Zeitstempel: </b> '.$ausgabe->zeitstempel.'<br>
			<b>Tags: </b> '.$ausgabe->tags.'<br>
			<b>Status: </b> '.$ausgabe->status.'<br>
			<b>Text: </b> '.$ausgabe->text.'<br>
			<b>Bildtitel: </b> '.$ausgabe->titel.'<br>
		';  
		echo '<hr><br>';
	}
}
?>
<br><a href='index.php?page=adminData'><button class='button'>Zurück</button></a><br>

