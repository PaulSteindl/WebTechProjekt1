<?php
    $ausgabe = $conn->getPost($_GET['cmt']);
?>

<!-- Post welcher Kommentiert wird-->
<div class="show_everything showComments ausgabe">
    <div class="col-md-3 userDetails">

        <?php 
            if($ausgabe->uid == $_SESSION['uid'] || $_SESSION['rolle'] == 0){
                
                echo "<a href='index.php?page=PostEdit&Bedit=$_GET[cmt]'><button class='button buttonComment'><span>Bearbeiten</span></button></a>";    
                echo "<a href='index.php?page=PostDel&Bdel=$_GET[cmt]'><button class='button buttonComment'><span>Löschen</span></button></a>";
            }
        ?>

        <img src="<?php echo $ausgabe->pfp ?>" class="pfp">
        
        <div class="username">
            <?php echo "<a href='index.php?page=profilePage&user=$ausgabe->username'>$ausgabe->username</a>" ?>
        </div>
        
        <div class="rolle">
            <?php echo $ausgabe->rolle ?>
        </div>
        
        <div class="user_time">
            Joined: <?php echo $ausgabe->user_time ?>
        </div>

        <div class="freigabe">
                <?php echo $ausgabe->status ?>
        </div>

        <div class="tag">
            <?php echo $ausgabe->tags ?>
        </div>

    </div>  

    <div class="col-md-9">
        <div class="TopPost">
            <?php echo '<h3>'.$ausgabe->titel.'</h3>' ?>
            <?php echo '<h4>'.$ausgabe->zeitstempel.'</h4>' ?>
            <hr>
        </div>

        <?php 
//wird textlich abgespeichert da die werte in der DB eigentlich nicht NULL sein dürfen
//entfernt aber lästige Probleme mit SQL abfrage
            if($ausgabe->thumbnail != 'NULL'){
                echo "<img class='pfp' src='$ausgabe->thumbnail'>";
            }else{
                echo "<p class='text'>$ausgabe->text</p>";
            }
        ?>   

                <div class="post-info">
                    <!-- if user likes post, style button differently -->
                    <i <?php if ($conn->userLiked($ausgabe->pid, $_SESSION['uid'])): ?>
                        class="fa fa-thumbs-up like-btn"
                    <?php else: ?>
                        class="fa fa-thumbs-o-up like-btn"
                    <?php endif ?>
                    data-id="<?php echo $ausgabe->pid ?>"></i>
                    <span class="likes"><?php echo $conn->getLikes($ausgabe->pid); ?></span>
                    
                    &nbsp;&nbsp;&nbsp;&nbsp;

                    <!-- if user dislikes post, style button differently -->
                    <i 
                    <?php if ($conn->userDisliked($ausgabe->pid, $_SESSION['uid'])): ?>
                        class="fa fa-thumbs-down dislike-btn"
                    <?php else: ?>
                        class="fa fa-thumbs-o-down dislike-btn"
                    <?php endif ?>
                    data-id="<?php echo $ausgabe->pid ?>"></i>
                    <span class="dislikes"><?php echo $conn->getDislikes($ausgabe->pid); ?></span>
                </div>
    </div>
    
    <div>
        <form action="index.php?page=comment&cmt=<?php echo $_GET['cmt'] ?>" method="POST" target="_self"> 
            <textarea id="commentSec" name="comment" placeholder="Was sind deine Gedanken dazu?" required></textarea>
            <!-- <input type="submit" name="submit"> -->
            <button class="button" type="submit" name="submit"><span>Submit </span></button>
        </form>
    </div>

</div>

<!--Kommentare-->
<?php
    if(isset($_POST['comment'])){
        $conn->InsertComment($_POST['comment'], $_SESSION['uid'], $_GET['cmt']);
    }


    $comments = $conn->getComments($_GET['cmt']);
    $i = 0;
    foreach($comments as $ausgabe){
    $i++;
?>
    <div>

        <div>
            <?php echo "$ausgabe->username ______________  $ausgabe->zeitstempel ______________ #$i";

                    echo "<br>$ausgabe->text <br>";

                if($_SESSION['uid'] == $ausgabe->uid || $_SESSION['rolle'] == 0){
                    if($_SESSION['uid'] == $ausgabe->uid  && isset($_GET['edi']) && !empty($_GET['edi']) && $ausgabe->kid == $_GET['edi']){
                ?>
                
                        <input type="submit" name="submit" value="Bestätigen">
                        </form>
                        <a href="index.php?cmt=<?php echo $_GET['cmt'] ?>"><button>Abbruch</button></a>

                <?php
                    }else{
//Löschen / bearbeiten optionen
                ?>
                        <a href="index.php?page=commentDelet&cmt=<?php echo $_GET['cmt'] ?>&del=<?php echo $ausgabe->kid ?>&uid=<?php echo $ausgabe->uid ?>">
                            <button>Löschen</button>
                        </a>
                <?php
                    }
                }
            ?>

        </div>

    </div>

<?php } ?>
