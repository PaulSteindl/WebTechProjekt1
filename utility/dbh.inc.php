<?php

    class db{
        
        private $localhost;
        private $user;
        private $passwordDb;
        private $databank;
        public $conn;
        
        public function __construct(){
            $this->localhost = "localhost";
            $this->user = "root";
            $this->passwordDb = "2sXnmxCqDJBwXt6j";
            $this->databank = "projekt";
        }
    
    //user Registrierung 
        //benoetigte variablen fuer die Registrierung
        public $anrede;
        public $vorname;
        public $nachname;
        public $username;
        public $password;
        public $passwordRepeat;
        public $email;
        //public $tel;
        public $geb;
        public $rolle;

        //connect to database
        private function ConnectDB(){
            $con = new mysqli($this->localhost, $this->user, $this->passwordDb, $this->databank);

            if (mysqli_connect_errno() != 0) {
                die("Die Datenbank konnte nicht erreicht werden. Foldender Fehler trat auf:".mysqli_connect_errno()." : ".mysqli_connect_error());
                exit();
            }else{
                return $con;
            }
        }
    
        function secureInputReg($anrede, $vorname, $nachname, $username, $password, $passwordRepeat, $email, $geb, $rolle){
            $conn = $this->ConnectDB();
      
            $this->anrede = mysqli_real_escape_string($conn, $anrede);
            $this->vorname = mysqli_real_escape_string($conn, $vorname);
            $this->nachname = mysqli_real_escape_string($conn, $nachname);
            $this->username = mysqli_real_escape_string($conn, $username);
            $this->password = mysqli_real_escape_string($conn, $password);
            $this->passwordRepeat = mysqli_real_escape_string($conn, $passwordRepeat);
            $this->email = mysqli_real_escape_string($conn, $email);
            $this->geb = mysqli_real_escape_string($conn, $geb);
            $this->rolle = $rolle;
            $conn->close();
        }
    
        function usernameExists($username, $email){
            $conn = $this->ConnectDB();
    
            $stmt = "SELECT * FROM users WHERE username = ? OR email = ?;";

            $sql = $conn->prepare($stmt);
            $sql->bind_param('ss', $username, $email);
            $sql->execute();
    
            $resultData = mysqli_stmt_get_result($sql);
            if($row = mysqli_fetch_assoc($resultData)) {
                return $row;
            } else {
                $result = false;
                return $result;
            }
    
            mysqli_stmt_close($stmt);
            $conn->close();
        }
    
        function createUser(){
            $conn = $this->ConnectDB();
            
            $stmt = "INSERT INTO users (anrede, vorname, nachname, username, passwort, email, geb, rolle) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";

            if(!$sql = $conn->prepare($stmt)){
                $conn->close();
                $error = 'stmtFailed';
                return $error;
            }
    
            $pwdHash = password_hash($this->password, PASSWORD_DEFAULT);
            $sql->bind_param('sssssssi', $this->anrede, $this->vorname, $this->nachname, $this->username, $pwdHash, $this->email, $this->geb, $this->rolle);
            $sql->execute();

            $conn->close();
            
            $error = 'regNone';
            return $error;
        }
    
    //login
    
        public $userDataLog;
        public $passwordLog;
        public $userDataRes;
    
        function secureInputLog($userDataLog, $passwordLog){
            $conn = $this->ConnectDB();
    
            $this->userDataLog = mysqli_real_escape_string($conn, $userDataLog);
            $this->passwordLog = mysqli_real_escape_string($conn, $passwordLog);
    
            $conn->close();
        }
    
        function secureInputRes($userDataRes){
            $conn = $this->ConnectDB();
            $this->userDataRes = mysqli_real_escape_string($conn, $userDataRes);
            $conn->close();
        }
        
        function loginCookie($uid){
            $conn = $this->ConnectDB();
            $sql = "SELECT username, rolle, inaktiv FROM users WHERE `uid` = ?;";

            $login = $conn->prepare($sql);
            $login->bind_param('i', $uid);
            $login->execute();
            $login->bind_result($username, $rolle, $inaktiv);
            $login->fetch();
            $conn->close();

            if($inaktiv == 0){
                $_SESSION["uid"] = $uid;
                $_SESSION["username"] = $username;
                $_SESSION['rolle'] = $rolle;
                $_SESSION['inaktiv'] = $inaktiv;
                $error = 'logNone';
                return $error;
            }else{
                $error = 'userInactive';
                return $error;
            }
        }

        function loginUser($angbleiben){
            $conn = $this->ConnectDB();
            
            $usernameExists = $this->usernameExists($this->userDataLog, $this->userDataLog);
    
            if($usernameExists == false){
                //user doesn't exist
                $error = 'wrongUsername';
                return $error;
                exit();
            }

            $pwdHash = $usernameExists['passwort'];
            if(password_verify($this->passwordLog, $pwdHash)){
                if($angbleiben == 'true'){
                    setcookie('angbleiben', $usernameExists['uid'], time()+604800);
                }
                $_SESSION["uid"] = $usernameExists["uid"];
                $_SESSION["username"] = $usernameExists["username"];
                $_SESSION['rolle'] = $usernameExists['rolle'];
                $_SESSION['inaktiv'] = $usernameExists['inaktiv'];
                //successfull, no errors
                $error = 'logNone';
                return $error;
                exit();
            }else {
                //right username, wrong password
                $error = 'wrongPwd';
                return $error;
                exit();
            }
            $conn->close();
        }
    
    //setting aender in php.ini, funktioniert sonst nicht
        function sendPwdReset() {
            $conn = $this->ConnectDB();

            $user = $this->usernameExists($this->userDataRes, $this->userDataRes);

            if($user == false){
                //user doesn't exist
                $error = 'wrongUsername';
                return $error;
                exit();
            }
    
            $uid = $user['uid'];
            $mailRecipient = $user['email'];
    
            $to = $mailRecipient;
            $subject = 'Your password is reset';
            $message = "Your new password is: 'Ab000000' \n Please go to your profile settings and change it immediatelly. \n\nYour Team";
            $headers = "From: fabiangazzia@gmail.com\r\n";
            
            if (mail($to, $subject, $message, $headers)) {
    
                $pwdHash = password_hash('Ab000000', PASSWORD_DEFAULT);
                $stmt = "UPDATE users SET passwort='$pwdHash' WHERE uid=$uid;";
                $sql = $conn->prepare($stmt);
                
                if($sql->execute()) {
                    $error = 'profPwdNone';
                    return $error;
                } else {
                    $error = 'stmtFailed';
                    return $error;
                }
    
            } else {
                $error = 'error';
                return $error;
            }
        }
    
    //profile page
        public $pwdOld;
        public $pwdNew;
        public $pwdNewRepeat;
        public $mailNew;
        public $usernameNew;
        //public $outputValue; //1 = mail, 2 = username
    
        function getProfData($uid){
            $conn = $this->ConnectDB();
            
            $stmt = "SELECT bid, username, passwort, email, zeitstempel, rolle, inaktiv FROM users WHERE uid=?;";
            $sql = $conn->prepare($stmt);
            $sql->bind_param('i', $uid);
            $sql->execute();

            $sql->bind_result($bid, $username, $passwort, $email, $zeitstempel, $rolle, $inaktiv);
            $userData = array();    

            for($i=0; $sql->fetch() ; $i++) {
                $uid = mysqli_real_escape_string($conn, $uid);
                $bid = mysqli_real_escape_string($conn, $bid);
                $username = mysqli_real_escape_string($conn, $username);
                $passwort = mysqli_real_escape_string($conn, $passwort);
                $email = mysqli_real_escape_string($conn, $email);
                $zeitstempel = mysqli_real_escape_string($conn, $zeitstempel);
                $rolle = mysqli_real_escape_string($conn, $rolle);
                $inaktiv = mysqli_real_escape_string($conn, $inaktiv);

                $userData[$i] = new userClass($uid, NULL, NULL, $bid, $username, $passwort, $email, NULL, $zeitstempel, $rolle, $inaktiv);  
            }

            $conn->close();
            return $userData;
        }
    
        function secureInputProfPwd($pwdOld, $pwdNew, $pwdNewRepeat){
            $conn = $this->ConnectDB();
            
            $this->pwdOld = mysqli_real_escape_string($conn, $pwdOld);
            $this->pwdNew = mysqli_real_escape_string($conn, $pwdNew);
            $this->pwdNewRepeat = mysqli_real_escape_string($conn, $pwdNewRepeat);
            $conn->close();
        }
    
        function secureInputProfMail($mailNew){
            $conn = $this->ConnectDB();
            
            $this->mailNew = mysqli_real_escape_string($conn, $mailNew);
            $conn->close();
        }
    
        function secureInputProfUid($usernameNew){
            $conn = $this->ConnectDB();
            
            $this->usernameNew = mysqli_real_escape_string($conn, $usernameNew);
            $conn->close();
        }
    
        function changeMail($uid){
            $conn = $this->ConnectDB();
            
            $stmt = "UPDATE users SET email='$this->mailNew' WHERE uid=$uid;";
            $sql = $conn->prepare($stmt);
            //$sql->bind_param('si', $this->mailNew, $uid);
        
            if($sql->execute()) {
                $error = 'profMailNone';
                return $error;
            } else {
                $error = 'stmtFailed';
                return $error;
            }

            $conn->close();
        }
    
        function changeUsername($uid) {
            $conn = $this->ConnectDB();
            
            $stmt = "UPDATE users SET username='$this->usernameNew' WHERE uid=$uid;";
            $sql = $conn->prepare($stmt);
            //$sql->bind_param('si', $this->mailNew, $uid);
        
            if($sql->execute()) {
                $error = 'profUsernameNone';
                $_SESSION['username'] = $this->usernameNew;
                return $error;
            } else {
                $error = 'stmtFailed';
                return $error;
            }
    
            $conn->close();
        }
    
        function changePwd($uid ){
            $conn = $this->ConnectDB();
    
            $pwdHash = password_hash($this->pwdNew, PASSWORD_DEFAULT);
            //$sql = "UPDATE users SET passwort='$pwdHash' WHERE uid=$uid;";
            
                $stmt = "UPDATE users SET passwort='$pwdHash' WHERE uid=$uid;";
                $sql = $conn->prepare($stmt);
                //$sql->bind_param('si', $this->mailNew, $uid);
          
                if($sql->execute()) {
                    $error = 'profPwdNone';
                    return $error;
                } else {
                    $error = 'stmtFailed';
                    return $error;
                }
    
                $conn->close();
        }
    
    //admin page
        function getAdminData() {
            $conn = $this->ConnectDB();
               
            $sql = "SELECT uid, vorname, nachname, bid, username, email, geb, DATE(zeitstempel), rolle, inaktiv 
                    FROM users;";

            $ausgabe = $conn->prepare($sql);
            $ausgabe->execute();
            $ausgabe->bind_result($uid, $vorname, $nachname, $bid, $username, $email, $geb, $zeitstempel, $rolle, $inaktiv);
            $adminData = array();
            
            for($i=0; $ausgabe->fetch() ; $i++) {
                $uid = mysqli_real_escape_string($conn, $uid);
                $vorname = mysqli_real_escape_string($conn, $vorname);
                $nachname = mysqli_real_escape_string($conn, $nachname);
                $bid = mysqli_real_escape_string($conn, $bid);
                $username = mysqli_real_escape_string($conn, $username);
                $email = mysqli_real_escape_string($conn, $email);
                $geb = mysqli_real_escape_string($conn, $geb);
                $zeitstempel = mysqli_real_escape_string($conn, $zeitstempel);
                $rolle = mysqli_real_escape_string($conn, $rolle);
                $inaktiv = mysqli_real_escape_string($conn, $inaktiv);

                $adminData[$i] = new userClass($uid, $vorname, $nachname, $bid, $username, NULL, $email, $geb, $zeitstempel, $rolle, $inaktiv);
            }

            $conn->close();
            return $adminData;
        }

    public function DisplayPost($start, $sort, $rolle, $search_string){
        $conn = $this->ConnectDB();
        $stop = 20 + 20 * $start;
        $start = 0 + 20 * $start;

            switch($sort){
                case'New':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p 
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND p.status >= ?
                            ORDER BY p.zeitstempel DESC
                            LIMIT $start, $stop;";
                break;

                case'NoPictures':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p 
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND b.bid = 1
                            AND p.status >= ?
                            ORDER BY p.zeitstempel DESC
                            LIMIT $start, $stop;";
                break;

                case'Old':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p 
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND p.status >= ?
                            ORDER BY p.zeitstempel ASC
                            LIMIT $start, $stop;";
                break;


                case'Pictures':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p 
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND b.bid != 1
                            AND p.status >= ?
                            ORDER BY p.zeitstempel DESC
                            LIMIT $start, $stop;";
                break;
                

                case'posts':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p 
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND p.text = '{$search_string}'
                            AND p.status >= ?
                            ORDER BY p.zeitstempel DESC";
                break;
                
                case'bildname':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p 
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND b.titel = '{$search_string}'
                            AND p.status >= ?
                            ORDER BY p.zeitstempel DESC;";
                break;

                case'kommentare':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p, kommentar k
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND k.text = '{$search_string}'
                            AND p.status >= ?
                            ORDER BY p.zeitstempel DESC";
                break;

                case'tag':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND p.tags = '{$search_string}'
                            AND p.status >= ?
                            ORDER BY p.zeitstempel DESC;";
                break;

                case'titel':
                    $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                            FROM users u, bild b, post p
                            WHERE u.uid = p.uid 
                            AND b.bid = p.bid
                            AND p.titel = '{$search_string}'
                            AND p.status >= ?
                            ORDER BY p.zeitstempel DESC";
                break;
            }

            $ausgabe = $conn->prepare($sql);
            $ausgabe->bind_param('i', $rolle);
            $ausgabe->execute();
            $ausgabe->bind_result($username, $uid, $pfp, $user_time, $rolle, $thumbnail, $original, $pid, $zeitstempel, $tags, $status, $text, $titel);
            $Posts = array();


            for($i=0; $ausgabe->fetch() ; $i++)
            {
                $username = mysqli_real_escape_string($conn, $username);
                $uid = mysqli_real_escape_string($conn, $uid);
                $pfp = mysqli_real_escape_string($conn, $pfp);
                $user_time = mysqli_real_escape_string($conn, $user_time);
                $rolle = mysqli_real_escape_string($conn, $rolle);
                $pid = mysqli_real_escape_string($conn, $pid);
                $zeitstempel = mysqli_real_escape_string($conn, $zeitstempel);
                $status = mysqli_real_escape_string($conn, $status);

                $pfp = $this->getProfilPic($pfp);
                $pfp = mysqli_real_escape_string($conn, $pfp);


                $Posts[$i] = new DisplayPostClass($username, $uid, $pfp, $user_time, $rolle, $thumbnail, $original, $pid, $zeitstempel, $tags, $status, $text, $titel);
            }
            
            $conn->close();
            return $Posts;
    }

    public function getPost($pid){
        $conn = $this->ConnectDB();
        $pid = mysqli_real_escape_string($conn, $pid);

        $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle, b.thumbnail, b.original, p.pid, DATE(p.zeitstempel), p.tags, p.status, p.text, p.titel 
                FROM users u, bild b, post p 
                WHERE u.uid = p.uid 
                AND b.bid = p.bid
                AND p.pid = ? ;";

        $ausgabe = $conn->prepare($sql);
        $ausgabe->bind_param('i', $pid);
        $ausgabe->execute();
        $ausgabe->bind_result($username, $uid, $pfp, $user_time, $rolle, $thumbnail, $original, $pid, $zeitstempel, $tags, $status, $text, $titel);
        $Post = array();
        $ausgabe->fetch();

        $username = mysqli_real_escape_string($conn, $username);
        $uid = mysqli_real_escape_string($conn, $uid);
        $pfp = mysqli_real_escape_string($conn, $pfp);
        $user_time = mysqli_real_escape_string($conn, $user_time);
        $rolle = mysqli_real_escape_string($conn, $rolle);
        $thumbnail = mysqli_real_escape_string($conn, $thumbnail);
        $original = mysqli_real_escape_string($conn, $original);
        $pid = mysqli_real_escape_string($conn, $pid);
        $zeitstempel = mysqli_real_escape_string($conn, $zeitstempel);
        $tags = mysqli_real_escape_string($conn, $tags);
        $status = mysqli_real_escape_string($conn, $status);
        $titel = mysqli_real_escape_string($conn, $titel);

        $pfp = $this->getProfilPic($pfp);
        $pfp = mysqli_real_escape_string($conn, $pfp);

        $Post = new DisplayPostClass($username, $uid, $pfp, $user_time, $rolle, $thumbnail, $original, $pid, $zeitstempel, $tags, $status, $text, $titel);
        
        $conn->close();
        return $Post;
    }

    public function getComments($pid){
        $conn = $this->ConnectDB();
        $pid = mysqli_real_escape_string($conn, $pid);  

        $sql = "SELECT u.username, u.bid, k.zeitstempel, k.text, k.uid, k.kid 
                FROM users u, kommentar k 
                WHERE u.uid = k.uid 
                AND k.pid = ?
                ORDER BY k.zeitstempel ASC;";

        $ausgabe = $conn->prepare($sql);
        $ausgabe->bind_param('i', $pid);
        $ausgabe->execute();
        $ausgabe->bind_result($username, $pfp, $zeitstempel, $text, $uid, $kid);
        $Kommentare = array();
        
        for($i=0; $ausgabe->fetch() ; $i++)
        {
            $username = mysqli_real_escape_string($conn, $username);
            $pfp = mysqli_real_escape_string($conn, $pfp);
            //gibt seltsame texte aus wie \\r\\n etc, andere sql injection stopung
            //$text = mysqli_real_escape_string($conn, $text);
            $uid = mysqli_real_escape_string($conn, $uid);
            $kid = mysqli_real_escape_string($conn, $kid);

            $Kommentare[$i] = new DisplayCommentClass($username, $pfp, $zeitstempel, $text, $uid, $kid);
        }

        $conn->close();
        return $Kommentare;
    }

    public function InsertComment($text, $uid, $pid){
        $conn = $this->ConnectDB();            

        $uid = mysqli_real_escape_string($conn, $uid);
        $pid = mysqli_real_escape_string($conn, $pid);

        $sql=  "INSERT INTO `kommentar` (`text`, `uid`, `pid`)
                VALUES (?, ?, ?)";

        $eingabe = $conn->prepare($sql);
        $eingabe->bind_param('sii', $text, $uid, $pid);
        $eingabe->execute();
        $conn->close();
    }

    public function DelCmt($kid){
        $conn = $this->ConnectDB();            
            $kid = mysqli_real_escape_string($conn, $kid);

            $sql=  "DELETE FROM kommentar WHERE kid = ?";

            $eingabe = $conn->prepare($sql);
            $eingabe->bind_param('i', $kid);
            $eingabe->execute();
            $conn->close();
    }


    public function getProfilPic($bid){
        if($bid != NULL){
            $conn = $this->ConnectDB();   

                $bid = mysqli_real_escape_string($conn, $bid);

                $sql=  "SELECT * FROM bild WHERE bid = ?;";

                $eingabe = $conn->prepare($sql);
                $eingabe->bind_param('i', $bid);
                $eingabe->execute();
                $eingabe->bind_result($bid, $thumbnail, $original, $text);
                $eingabe->fetch();
                $conn->close();
                
                return $thumbnail;
        }else{
            $defaultpic = 'img/thumbnail/NICHT_LOESCHEN.png';
            return $defaultpic;
        }
    }

    public function DeletPost($pid, $uid, $rolle){
        $conn = $this->ConnectDB();

            $uid = mysqli_real_escape_string($conn, $uid);
            $pid = mysqli_real_escape_string($conn, $pid);

            if($uid != NULL && $rolle == 0){
                $sql = "DELETE FROM `post` WHERE `pid` = $pid;";
            }else{
                $sql = "DELETE FROM `post` WHERE `pid` = $pid AND `uid` = $uid;";
            }

            var_dump($sql);
            $del = $conn->prepare($sql);
            $del->execute();           
            $conn->close();
    }

    // Get total number of likes for a particular post
    function getLikes($pid){
        $conn = $this->ConnectDB();

        $pid = mysqli_real_escape_string($conn, $pid);

        $sql = "SELECT COUNT(*) FROM rating_info 
                WHERE pid = ? AND rating_action='like'";

        $rs = $conn->prepare($sql);
        $rs->bind_param('i', $pid);
        $rs->execute();
        $rs->bind_result($result);
        for($i = 0; $rs->fetch(); $i++){
            $ausgabe[$i] = $result;
        }
        $conn->close();
        return $ausgabe[0];
    }

    // Get total number of dislikes for a particular post
    function getDislikes($pid){
        $conn = $this->ConnectDB();

        $pid = mysqli_real_escape_string($conn, $pid);

        $sql = "SELECT COUNT(*) FROM rating_info 
                WHERE pid = ? AND rating_action='dislike'";
        $rs = $conn->prepare($sql);
        $rs->bind_param('i', $pid);
        $rs->execute();
        $rs->bind_result($result);
        for($i = 0; $rs->fetch(); $i++){
            $ausgabe[$i] = $result;
        }
        $conn->close();
        return $ausgabe[0];
    }

    // Get total number of likes and dislikes for a particular post
    function getRating($pid){
        $conn = $this->ConnectDB();

        $pid = mysqli_real_escape_string($conn, $pid);

        $rating = array();
        $likes_query = "SELECT COUNT(*) FROM rating_info WHERE pid = $pid AND rating_action='like'";
        $dislikes_query = "SELECT COUNT(*) FROM rating_info 
                            WHERE pid = $pid AND rating_action='dislike'";
        $likes_rs = mysqli_query($conn, $likes_query);
        $dislikes_rs = mysqli_query($conn, $dislikes_query);
        $likes = mysqli_fetch_array($likes_rs);
        $dislikes = mysqli_fetch_array($dislikes_rs);
        $rating = [
            'likes' => $likes[0],
            'dislikes' => $dislikes[0]
        ];
        $conn->close();
        return json_encode($rating);
    }

    // Check if user already likes post or not
    function userLiked($pid, $uid){
        $conn = $this->ConnectDB();

        $pid = mysqli_real_escape_string($conn, $pid);
        $uid = mysqli_real_escape_string($conn, $uid);

        $sql = "SELECT COUNT(*) FROM rating_info WHERE uid= ? 
                AND pid= ? AND rating_action='like'";
        $rs = $conn->prepare($sql);
        $rs->bind_param('ii', $uid, $pid);
        $rs->execute();
        $rs->bind_result($result);
        $rs->fetch();
        $conn->close();
        if ($result > 0) {
            return true;
        }else{
            return false;
        }
    }

    // Check if user already dislikes post or not
    function userDisliked($pid, $uid){
        $conn = $this->ConnectDB();

        $pid = mysqli_real_escape_string($conn, $pid);
        $uid = mysqli_real_escape_string($conn, $uid);

        $sql = "SELECT COUNT(*) FROM rating_info WHERE uid= ?
                AND pid= ? AND rating_action='dislike'";
        $rs = $conn->prepare($sql);
        $rs->bind_param('ii', $uid, $pid);
        $rs->execute();
        $rs->bind_result($result);
        $rs->fetch();
        $conn->close();
        if ($result > 0) {
            return true;
        }else{
            return false;
        }
    }

    // if user clicks like or dislike button
    function registerClick($uid){
        $conn = $this->ConnectDB();

        $uid = mysqli_real_escape_string($conn, $uid);

        if (isset($_POST['action'])) {
            $pid = $_POST['pid'];

            $pid = mysqli_real_escape_string($conn, $pid);

            $action = $_POST['action'];
            switch ($action) {
                case 'like':
                $sql="INSERT INTO rating_info (uid, pid, rating_action) 
                        VALUES ($uid, $pid, 'like') 
                        ON DUPLICATE KEY UPDATE rating_action='like'";
                break;
                case 'dislike':
                    $sql="INSERT INTO rating_info (uid, pid, rating_action) 
                        VALUES ($uid, $pid, 'dislike') 
                        ON DUPLICATE KEY UPDATE rating_action='dislike'";
                break;
                case 'unlike':
                    $sql="DELETE FROM rating_info WHERE uid=$uid AND pid=$pid";
                    break;
                case 'undislike':
                    $sql="DELETE FROM rating_info WHERE uid=$uid AND pid=$pid";
                break;
                default:
                    break;
            }
        
    // execute query to effect changes in the database ...
            mysqli_query($conn, $sql);
            echo $this->getRating($pid);
            $conn->close();
            exit(0);
        }
    }

    function GetProfile($username){
        $conn = $this->ConnectDB();
        $username = mysqli_real_escape_string($conn, $username);

        $sql = "SELECT u.username, u.uid, u.bid, DATE(u.zeitstempel), u.rolle FROM users u WHERE u.username = ?;";

        $profile = $conn->prepare($sql);
        $profile->bind_param('s', $username);
        $profile->execute();
        $profile->bind_result($username, $uid, $bid, $date, $rolle);
        $profile->fetch();
        $conn->close();

        $pfp = $this->getProfilPic($bid);
        $result = new ProfilePage($username, $uid, $pfp, $date, $rolle);

        return $result;
    }

    function SafePostEdit($pid, $uid, $status, $tags, $titel, $text){
        $conn = $this->ConnectDB();

        $pid = mysqli_real_escape_string($conn, $pid);
        $uid = mysqli_real_escape_string($conn, $uid);
        $status = mysqli_real_escape_string($conn, $status);
        $tags = mysqli_real_escape_string($conn, $tags);

        $sql = "UPDATE post SET status = ?, tags = ?, titel = ?, text = ? WHERE pid = ? AND uid = ?";

        $update = $conn->prepare($sql);
        $update->bind_param('isssii',$status, $tags, $titel, $text, $pid, $uid);
        $update->execute();
        $conn->close();

    }

    function changeToInactive($uid){
        $conn = $this->ConnectDB();
        $uid = mysqli_real_escape_string($conn, $uid);


        $sql = "UPDATE users SET inaktiv = 1 WHERE uid = ?";
        $update = $conn->prepare($sql);
        $update->bind_param('i', $uid);
        $update->execute();
        $conn->close();

    }

    function changeToActive($uid){
        $conn = $this->ConnectDB();
        $uid = mysqli_real_escape_string($conn, $uid);


        $sql = "UPDATE users SET inaktiv = 0 WHERE uid = ?";
        $update = $conn->prepare($sql);
        $update->bind_param('i', $uid);
        $update->execute();
        $conn->close();
    }

    function getHistory($uid){
        $conn = $this->ConnectDB();
        $uid = mysqli_real_escape_string($conn, $uid);

        $sql = "SELECT * FROM post WHERE uid = ?;";
        $abfrage = $conn->prepare($sql);
        $abfrage->bind_param('i', $uid);
        $abfrage->execute();
        $abfrage->bind_result($pid, $uid, $bid, $zeitstempel, $tags, $status, $text, $titel);
        
        for($i = 0; $abfrage->fetch(); $i++){
            $pid = mysqli_real_escape_string($conn, $pid);
            $uid = mysqli_real_escape_string($conn, $uid);
            $bid = mysqli_real_escape_string($conn, $bid);
            $zeitstempel = mysqli_real_escape_string($conn, $zeitstempel);
            $tags = mysqli_real_escape_string($conn, $tags);
            $status = mysqli_real_escape_string($conn, $status);
            $text = mysqli_real_escape_string($conn, $text);
            $titel = mysqli_real_escape_string($conn, $titel);

            $ausgabe[$i] = new PostHistory($pid, $uid, $bid, $zeitstempel, $tags, $status, $text, $titel);
        }

        $conn->close();
        if(isset($ausgabe) && $ausgabe != NULL){
            return $ausgabe;
        }else return NULL;
    }

    /*// function für FileUpload
	public function FileUpload($title, $pname){
		$conn = new mysqli($this->localhost, $this->user, $this->passwordDb, $this->databank);
		
		if(mysqli_connect_errno() == 0){
			$title = mysqli_real_escape_string($conn, $title);
			$pname = mysqli_real_escape_string($conn, $pname);
			
			$sql = "INSERT INTO post(bildtitel, bid) VALUES ('$title', '$pname')";
			//$eingabe = $conn->prepare($sql);
			//$eingabe->bind_param('ss', $title, $pname);
			//$eingabe->execute();
			
			if(mysqli_query($conn, $sql)){
				echo "<br>Ready!";
			}
			else{
				echo "<br>Error!";
			}
			$conn->close();
		}
		else{
			echo "<br>Error, file not uploaded!<br>This type of file can not be uploaded!";
		}
	}*/
    
    public function Getbid($pfad){
        $conn = $this->ConnectDB();
        $sql = "SELECT bid FROM bild WHERE original = ?";
            $ausgabe = $conn->prepare($sql);
            $ausgabe->bind_param('s', $pfad);
            $ausgabe->bind_result($bid);
            $ausgabe->execute();
            $ausgabe->fetch();
            $conn->close();

            return $bid;
    }

	// function für Beitrag erstellen BeitrFreig_Auswahl
	public function BeitrFreig_Auswahl($uid, $titel, $visibility, $tags, $textarea, $pfad){
        $conn = $this->ConnectDB();

        $visibility = mysqli_real_escape_string($conn, $visibility);
        $uid = mysqli_real_escape_string($conn, $uid);

        $bid = $this->Getbid($pfad);
        
        $sql2 = "INSERT INTO post (uid, bid, titel, status, tags, text) VALUES (?, ?, ?, ?, ?, ?);";
        
        $eingabe = $conn->prepare($sql2);
        $eingabe->bind_param('iisiss',$uid, $bid, $titel, $visibility, $tags, $textarea);
        $eingabe->execute();

        $conn->close();
	}
	
	// function für Select abfrage BeitrFreig_Auswahl
	public function SelectBeitrFreig_Auswahl(){
        $conn = $this->ConnectDB();

        $sql = "SELECT vorname, nachname FROM users;";
        
        $ausgabe = $conn->prepare($sql);
        $ausgabe->execute();
        $ausgabe->bind_result($vorname, $nachname);
        $adminData = array();
        
        for($i=0; $ausgabe->fetch(); $i++) {
            $vorname = mysqli_real_escape_string($conn, $vorname);
            $nachname = mysqli_real_escape_string($conn, $nachname);

            $adminData[$i] = new DisplayPostClass($vorname, $nachname, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
        }
        $conn->close();
        return $adminData;
    
    }
    
    public function GetPicIntoOriginal($pfad, $pictureName){
        $conn = $this->ConnectDB();

        $pictureName = mysqli_real_escape_string($conn, $pictureName);

        $sql = "INSERT INTO bild(`thumbnail`, `original`, `titel`) VALUES ('NULL', ?, ?)";

        $upload = $conn->prepare($sql);
        $upload->bind_param('ss', $pfad, $pictureName);
        $upload->execute();
        $conn->close();
    }

    public function CreateThumb($path, $pictureName, $maxthumbwidth, $maxthumbheight){
        $imagefile = getimagesize($path);

        switch($imagefile[2]){
            // Codes für die populärsten Bildformate
            // 1 = GIF, 2 = JPG, 3 = PNG
            case 1: // GIF
                $image = imagecreatefromgif($path);
            break;

            case 2: // JPEG
                $image = imagecreatefromjpeg($path);
            break;

            case 3: // PNG
                $image = imagecreatefrompng($path);
            break;

            default:
                die('Bildformat nicht erlaubt');
        }

        $thumbwidth = $imagefile[0];
        $thumbheight = $imagefile[1];

        // Breite skalieren falls nötig
        if ($thumbwidth > $maxthumbwidth){
            $factor = $maxthumbwidth / $thumbwidth;
            $thumbwidth = round($imagefile[0] * $factor);
            $thumbheight = round($imagefile[0] * $factor);
        }
        // Höhe skalieren, falls nötig
        if ($thumbheight > $maxthumbheight){
            $factor = $maxthumbheight / $thumbheight;
            $thumbwidth = round($thumbwidth * $factor);
            $thumbheight = round($thumbheight * $factor);
        }

        $thumb = imagecreatetruecolor($thumbwidth, $thumbheight);

        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumbwidth, $thumbheight, $imagefile[0], $imagefile[1]);
        $destination = 'img/thumbnail/'.$pictureName;

        //speichert thumbnail ab
        imagepng($thumb, $destination);

        //leert zwischenspeichr
        imagedestroy($thumb);
        imagedestroy($image);

        //wird in DB abgespeichert
        $conn = $this->ConnectDB();
        $src = 'img/pic/'.$pictureName;

        $sql = "UPDATE `bild` SET `thumbnail` = ? WHERE `original` = ? AND `thumbnail` = 'NULL';";
        
        $upload = $conn->prepare($sql);
        $upload->bind_param('ss', $destination, $src);
        $upload->execute();
        $conn->close();
        
    }

    public function LoadBidInPfp($pfad, $uid){
        $conn = $this->ConnectDB();

        $bid = $this->Getbid($pfad);

        $sql = "UPDATE `users` SET `bid` = ? WHERE `uid` = ?;";

        $update = $conn->prepare($sql);
        $update->bind_param('ii', $bid, $uid);
        $update->execute();
        $conn->close();

        return true;
    }
}
?>