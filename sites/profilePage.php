<?php
    $profile = $conn->GetProfile($_GET['user']);
?>

<div class="container-fluid">
    <div class="row">
        <h1>Profile Page</h1><hr>
        <div class="col-sm">
            <!-- prof img -->
            <img src="<?php echo $profile->pfp ?>" alt="Profile picture"  id="PfPimg">

            <h3 class=''>Username</h3>
            <?php
                echo $profile->username;
            ?>

            <h3 class=''>Join date</h3>
            <?php
                echo $profile->date.'<br>';
            ?>

            <h3 class=''>Role</h3>
            <?php
                switch ($profile->rolle) {
                    case 0:
                        echo 'Admin';
                        break;
                    case 1:
                        echo 'User';
                        break;
                    case 2:
                        echo 'Moderator';
                        break;
                    default:
                        echo 'User';
                        break;
                }
            ?>
        </div>

    </div>
</div>