<?php
    $ausgabe = $conn->getPost($_GET['Bedit']);

    if(!isset($_SESSION['uid']) || $_SESSION['uid'] != $ausgabe->uid){
        header("Location: index.php");
        exit();
    }
?>

<!-- Post welcher Editiert wird-->
<div class="show_everything row">
    <div class="col-md-3 userDetails">
        
        <img src="<?php echo $ausgabe->pfp ?>" class="pfp">
        
        <div class="username">
            <?php echo "<a href=''>$ausgabe->username</a>" ?>
        </div>
        
        <div class="rolle">
            <?php echo $ausgabe->rolle ?>
        </div>
        
        <div class="user_time">
            Joined: <?php echo $ausgabe->user_time ?>
        </div>

        <form action="index.php?page=SafePostEdit&Bedit=<?php echo $_GET['Bedit'] ?>" method="POST">

            <div class="freigabe postedit">
                <select name="status postedit">
                    <option value="0">privat</option>
                    <option value="1">regestriert</option>
                    <option value="2">öffentlich</option>
                </select>
            </div>

            <div class="tag  postedit">
                <input type="text" name="tags" value="<?php echo $ausgabe->tags ?>">
            </div>

        </div>  

        <div class="col-md-9">
            <div class="TopPost  postedit">
                <input type="text" name="titel" value="<?php echo $ausgabe->titel ?>">
                <?php echo '<h4>'.$ausgabe->zeitstempel.'</h4>' ?>
                <hr>
            </div>

            <?php 
//wird textlich abgespeichert da die werte in der DB eigentlich nicht NULL sein dürfen
//entfernt aber lästige Probleme mit SQL abfrage
                if($ausgabe->thumbnail != 'NULL'){
                    echo "<img src='$ausgabe->thumbnail'>";
                }
                    echo " <div class='postedit'><input type='textarea' name='PostText' value='$ausgabe->text'></div>";
                ?>   
        </div>
        <button class="button" id="buttonPostEdit" type="submit" value="Speichern"><span>Speichern </span></button>
    </form>
    <a href="index.php?page=beitragsanzeige"><button class="button" id="buttonPostEdit" ><span>Abbrechen</span></button></a>
</div>
    