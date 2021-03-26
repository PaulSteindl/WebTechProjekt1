<?php
    class userClass{
        
        public $uid;
        public $vorname;
        public $nachname;
        public $bid;
        public $username;
        public $passwort;
        public $email;
        public $geb;
        public $zeitstempel;
        public $rolle;
        public $inaktiv;

        public function __construct($uid, $vorname, $nachname, $bid, $username, $passwort, $email, $geb, $zeitstempel, $rolle, $inaktiv)
        {
            $this->uid = $uid;
            $this->vorname = $vorname;
            $this->nachname = $nachname;
            $this->bid = $bid;
            $this->username = $username;
            $this->passwort = $passwort;
            $this->email = $email;
            $this->geb = $geb;
            $this->zeitstempel = $zeitstempel;

            switch($rolle){
                case 0:
                    $this->rolle = "Admin";
                break;

                case 1:
                    $this->rolle = "User";
                break;
            }
            
            switch($inaktiv){
                case 0:
                    $this->inaktiv = "active";
                break;
                
                case 1:
                    $this->inaktiv = "inactive";
                break;
            }
        }
    }
?>