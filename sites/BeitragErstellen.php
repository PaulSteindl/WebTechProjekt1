<h1>Beitrag erstellen</h1><hr><br>

<form action="index.php?page=neuerPost" method="post" enctype="multipart/form-data">
<!-- File -->
	<div class="moinMeister">
		<!--<h3 class='posth4'>Titel des Bildes</h3><br>
		<input type="text" name="title" pattern="[a-zA-Z0-9!.?,()-:]{1,}" title="Please only user allowed symbols('!', '.', ':', ',', '(', '(', '-', ':').">
		<br>-->
		<h3 class='posth4'>File Upload</h3>
		<input type="File" name="file">
	</div>

<!-- Beitrag -->
	<div class="textarea">
		
		<h3 class='posth4'>Titel des Beitrags:</h3>
		<input type="text" name="titel" id="titel" placeholder="Title..." pattern="[a-zA-Z0-9!.?,()-: ]{1,}" title="Please only user allowed symbols('!', '.', ':', ',', '(', '(', '-', ':').">
		
		<h3 class='posth4'>Tags:</h3>
		<textarea name="tags" id="tags" placeholder="Tags (optional)..." pattern="[a-zA-Z0-9!.?,()-: ]{1,}" title="Please only user allowed symbols('!', '.', ':', ',', '(', '(', '-', ':')."></textarea>

		<h3 class='posth4'>Text:</h3>
		<textarea name="textarea" id="textarea" placeholder="Text..." pattern="[a-zA-Z0-9!.?,()-: ]{1,}" title="Please only user allowed symbols('!', '.', ':', ',', '(', '(', '-', ':')." required></textarea>
	</div>
	<br>
 
<!-- Veröffentlichung -->
	<div class="select">
		<select name="visibility" required>
			<option value="2" name="everyone">Öffentlich</option>
			<option value="1" name="restricted">Regestriert</option>
			<option value="0" name="private">Privat</option>
		</select>
		<!-- <input type="submit" name="submit" value="Beitrag erstellen"> -->
		<button class="button" type="submit" name="submit" style="width: 150px;"><span>Beitrag erstellen </span></button>
	</div>
</form>

<?php
// function für Select abfrage BeitrFreig_Auswahl.php
$conn->SelectBeitrFreig_Auswahl();

//Benutzer ist kein Gast
if(isset($_SESSION['uid']) && isset($_POST['submit'])){
	$titel = $_POST['titel'];
	$visibility = $_POST['visibility'];
	$tags = $_POST['tags'];
	$textarea = $_POST['textarea'];
	
	if(isset($_POST['visibility']) && isset($_POST['submit'])){
		if(isset($_FILES["file"]) && !empty($_FILES['file'] && $_FILES['file']['error'] == 0)){
			$file = $_FILES["file"];
			$pictureName = $_FILES["file"]["name"];
			$pictureExt = explode('.', $pictureName); // Teilt eine Zeichenkette anhand einer Zeichenkette
			$pictureActualExt = strtolower(end($pictureExt)); //Setzt einen String in Kleinbuchstaben um
			$allowed = array('jpg', 'jpeg', 'png', 'gif'); // nur diese Bildtypen sind erlaubt zum uploaden
	
			if(in_array($pictureActualExt, $allowed)){
				
				// filename with a random number so that similiar don't get replaced
				$pname = rand(1000,10000)."-".$_FILES["file"]["name"];
				
				// temporary filename to store file
				$tname = $_FILES["file"]["tmp_name"];
				
				// upload directory path
				$uploads_dir = 'img/pic';

				//full path
				$fullpath = $uploads_dir.'/'.$pname;
				
				// TO move the uploaded file to specific location
				move_uploaded_file($tname, $fullpath);

				$conn->GetPicIntoOriginal($fullpath, $titel);
				$conn->CreateThumb($fullpath, $pname, 900, 900);
				$conn->BeitrFreig_Auswahl($_SESSION['uid'], $titel, $visibility, $tags, $textarea, $fullpath);
				echo "Beitrag wurde erfolgreich erstellt";
			}
		}elseif($_FILES['file']['error'] == 4){

			// function für Beitrag erstellen BeitrFreig_Auswahl
			$conn->BeitrFreig_Auswahl($_SESSION['uid'], $titel, $visibility, $tags, $textarea, 'NULL');
			echo "Beitrag wurde erfolgreich erstellt";
		}
	}
	else{
		echo "Keinen Parameter ausgewählt";
	}
}
//abfrage für fehlend
else{
	echo "<br>Bitte alle benötigten Daten eintragen um einen Beitrag zu erstellen!";
}

?>