<h1>User Data for Admin</h1><hr><br>
<?php
//wird gescaut ob man Admin ist, wenn nicht weiterleitung
	if (isset($_SESSION['rolle'])) {
		if ($_SESSION['rolle'] == 0) {
			$ausgabe = $conn->getAdminData();
			
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

echo '<div class=" showAllAdmin">';
			$i = 0;
//gibt users aus
			foreach($ausgabe as $row){

				$i++;
		
				echo 'Uid: '.$row->uid;
					echo '<br>';
				echo 'Vorname: '.$row->vorname;
					echo '<br>';	
				echo 'Nachname: '.$row->nachname;
					echo '<br>';
				echo 'Username: '.$row->username;
					echo '<br>';
				echo 'E-Mail: '.$row->email;
					echo '<br>';	
				echo 'Join date: '.$row->zeitstempel;
					echo '<br>';
				echo 'Rolle: '.$row->rolle;
					echo '<br>';
				echo 'Aktiv: '.$row->inaktiv;

				?>
				<form action="index.php?page=adminDataChange" method="POST">
					<div class="">
						<input type="hidden" value="<?php echo $row->uid ?>" name="ChangeUid">
						<input type="hidden" value="<?php echo $row->inaktiv ?>" name="ChangeActivity">
						<label>Change activity <button class="button" type="submit" name="submitAdmin" value="<?php $row->uid ?>"><span>Change</span></button></label><br>
					</div>
				</form>

				<form action="index.php?page=adminDataChange" method="POST">
					<input type="hidden" value="<?php echo $row->username ?>" name="ChangeUsername">
					<!-- <input type="hidden" value="<?php echo $row->inaktiv ?>" name="ChangePwd"> -->
					<label>Reset password for this user <button class="button" type="submit" name="submitAdminPwd" value="<?php $row->username ?>"><span>Change</span></button></label>
				</form>

				<label for="anzeigen">Show Post history</label>
				<a href="index.php?page=UserHistory&history=<?php echo $row->uid ?>"><button class="button" name="anzeigen">Anzeigen</button></a>

				<?php
				echo'<hr>';

//passwort reset
				// if (isset($_POST['submitAdminPwd']) && !empty($_POST['submitAdminPwd'])) {
				// 	//echo $row->username;
				// 	$conn->secureInputRes($row->username);
				// 	$error = $conn->sendPwdReset();
				// 	header("Location: ./index.php?page=adminData&error=$error");
				// 	exit();
				// } else {
				// 	//echo 'testkacke';
				// }
			}
echo '</div>';
?>