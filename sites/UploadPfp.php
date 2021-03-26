<h1>Profile Picture</Picture></h1><hr><br>

<form action="index.php?page=ChangePfP" method="post" enctype="multipart/form-data">
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
		
	</div>
	<br>

		<!-- <input type="submit" name="submit" value="Profil Bild aktualisieren"> -->
		<button class="button" type="submit" name="submit" style="width: 150px;"><span>Aktualisieren </span></button>
</form>

<?php
// function für Select abfrage BeitrFreig_Auswahl.php
$conn->SelectBeitrFreig_Auswahl();

//Benutzer ist kein Gast
if(isset($_SESSION['uid']) && isset($_POST['submit']) && isset($_FILES['file']) && !empty($_FILES['file'])){
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

            $conn->GetPicIntoOriginal($fullpath, $pictureName);
            $conn->CreateThumb($fullpath, $pname, 300, 300);
            if($conn->LoadBidInPfp($fullpath, $_SESSION['uid'])){
                echo "Profilbild wurde erfolgreich aktualisiert";
            };
        }
	}
	else{
		echo "Es ist ein fehler beim hochladen aufgetreten";
	}
}


?>