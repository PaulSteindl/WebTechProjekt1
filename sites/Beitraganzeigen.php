<!--Zum suchen-->
<div class="container-fluid">
    <div class="row searchArea">
        <div class="col-sm-12">
            <form name="search_form" method="POST" class="search" action="index.php?page=beitragsanzeige">
                Search: <input type="text" name="search_box" value=""/>
                <select name="search_option">
                    <option value="posts">Beiträge</option>
                    <option value="bildname">Bildname</option>
                    <option value="kommentare">Kommentare</option>
                    <option value="tag">Tags</option>
                    <option value="titel">Titel</option>
                </select>
                <input type="submit" name="search" value="Search">
            </form>
            <!--Zum sortieren-->
            <div class="sortbuttons container-fluid">
                <div class="row">
                    <div class="col-sm-3">
                        <a href="index.php?page=beitragsanzeige&sort=New">Newest</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="index.php?page=beitragsanzeige&sort=Old">Oldest</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="index.php?page=beitragsanzeige&sort=Pictures">Pictures</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="index.php?page=beitragsanzeige&sort=NoPictures">NoPictures</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

//hier wird abgefragt welche beiträge geholt werden und wie sie sortiert sind
    if(isset($_SESSION['rolle'])){
        $rolle = $_SESSION['rolle'];
    }else{
        $rolle = 2;
    }

    if(!isset($_POST['search_box'])){
        if(isset($_GET['sort'])){
            if(!isset($_GET['site'])){
                $ausgabe = $conn->DisplayPost(0, $_GET['sort'], $rolle, NULL);
                $_GET['site'] = 0;
            }else{
                $ausgabe = $conn->DisplayPost($_GET['site'], $_GET['sort'], $rolle, NULL);
            }
        }else{
            if(!isset($_GET['site'])){
                $ausgabe = $conn->DisplayPost(0, 'New', $rolle, NULL);
                $_GET['site'] = 0;
            }else{
                $ausgabe = $conn->DisplayPost($_GET['site'], 'New', $rolle, NULL);
            }
        }
    }else{
        $ausgabe = $conn->DisplayPost(0, $_POST['search_option'], $rolle, $_POST['search_box']);
    }
    $i = 0;
//schleife um Posts auszugeben
    foreach($ausgabe as $row){
        $i++;
?>  

    <div class="show_everything row">
        <div class="col-md-3 userDetails">
            <!-- neuer code - style geloescht -->
            <img src="<?php echo "$row->pfp" ?>" class="pfp">
            
            <div class="username">
                <?php echo "<a href='index.php?page=profilePage&user=$row->username'>$row->username</a>" ?>
            </div>
            
            <div class="rolle">
                <?php echo $row->rolle ?>
            </div>
            
            <div class="user_time">
                Joined: <?php echo $row->user_time ?>
            </div>

            <div class="freigabe">
                <?php echo $row->status ?>
            </div>

            <div class="tag">
                <?php echo $row->tags ?>
            </div>

        </div>  

        <div class="col-md-9">
            <div class="TopPost">
                <?php echo '<h3>'.$row->titel.'</h3>' ?>
                <?php echo '<h4>'.$row->zeitstempel.'</h4>' ?>
                <hr>
            </div>

            <?php 
//wird textlich abgespeichert da die werte in der DB eigentlich nicht NULL sein dürfen
//entfernt aber lästige Probleme mit SQL abfrage
                if($row->original != 'NULL'){
                    //neuer code - statt #, id=
                    // echo "<img src='$row->thumbnail' #PostImg'>";
                    echo "<img src='$row->thumbnail' id='PostImg'>";

                }
                    echo "<p class='text'>$row->text</p>";
            ?>

            <?php if(isset($_SESSION['uid'])){?>
                <div class="post-info">
                    <!-- if user likes post, style button differently -->
                    <i <?php if ($conn->userLiked($row->pid, $_SESSION['uid'])): ?>
                        class="fa fa-thumbs-up like-btn"
                    <?php else: ?>
                        class="fa fa-thumbs-o-up like-btn"
                    <?php endif ?>
                    data-id="<?php echo $row->pid ?>"></i>
                    <span class="likes"><?php echo $conn->getLikes($row->pid); ?></span>
                    
                    &nbsp;&nbsp;&nbsp;&nbsp;

                    <!-- if user dislikes post, style button differently -->
                    <i 
                    <?php if ($conn->userDisliked($row->pid, $_SESSION['uid'])): ?>
                        class="fa fa-thumbs-down dislike-btn"
                    <?php else: ?>
                        class="fa fa-thumbs-o-down dislike-btn"
                    <?php endif ?>
                    data-id="<?php echo $row->pid ?>"></i>
                    <span class="dislikes"><?php echo $conn->getDislikes($row->pid); ?></span>
                </div>

                <a href="index.php?page=comment&cmt=<?php echo $row->pid ?>">
                    <button id="comment" class="btn btn-outline-primary button cmt">Kommentieren</button>
                </a>    
            <?php } ?>
        </div>
    </div>
<?php 
//ende vom foreach
    }

//Zum navigieren
    if(isset($_GET['sort'])){
        if(!isset($_GET['site'])){
            if($i == 20){
                echo "<a href='index.php?page=beitragsanzeige&site=1&sort=$_GET[sort]'>weiter</a>";
            }
        }else{
            if($i == 20){
                echo "<a href='index.php?page=beitragsanzeige&site=$_GET[site]&sort=$_GET[sort]'>weiter</a>";
            }
        }   
    }else{
        if(!isset($_POST['search_box'])){
            if(!isset($_GET['site']) || $_GET['site'] == 0){
                if($i == 20){
                    echo "<a href='index.php?page=beitragsanzeige&site=1'>weiter</a>";
                }
            }else{
                $_GET['site']--;
                echo "<a href='index.php?page=beitragsanzeige&site=$_GET[site]'>zurück</a>  ";
                if($i == 20){
                    $_GET['site']++;
                    $_GET['site']++;
                    echo "<a href='index.php?page=beitragsanzeige&site=$_GET[site]'>weiter</a>";
                }    
            }
        }
    }
?>