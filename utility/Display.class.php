<?php
    class DisplayPostClass{
        public $username;
        public $uid;
        public $pfp;
        public $user_time;
        public $rolle; 
        public $thumbnail;
        public $original;
        public $pid;
        public $zeitstempel;
        public $tags; 
        public $status;
        public $text; 
        public $titel;

        public function __construct($username, $uid, $pfp, $user_time, $rolle, $thumbnail, $original, $pid, $zeitstempel, $tags, $status, $text, $titel)
        {
            $this->username =  $username;
            $this->uid = $uid;
            $this->pfp = $pfp;
            $this->user_time = date($user_time);

            switch($rolle){
                case 0:
                    $this->rolle = "Admin";
                break;
                
                case 1:
                    $this->rolle = "User";
                break;
            }

            $this->thumbnail = $thumbnail;
            $this->original = $original;
            $this->pid = $pid;
            $this->zeitstempel = $zeitstempel;
            $this->tags = $tags; 

            switch($status){
                case 0:
                    $this->status = "privat";
                break;

                case 1:
                    $this->status = "registriert";
                break;

                case 2:
                    $this->status = "öffentlich";
                break;
            }

            $this->text = $text; 
            $this->titel = $titel;
        }
    }
?>