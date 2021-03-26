<?php
    class ProfilePage{
        public $username;
        public $uid;
        public $pfp;
        public $date;
        public $rolle;
    
        public function __construct($username, $uid, $pfp, $date, $rolle){
            $this->username = $username;
            $this->uid = $uid;
            $this->pfp = $pfp;
            $this->date = $date;
            $this->rolle = $rolle;
        }
    }