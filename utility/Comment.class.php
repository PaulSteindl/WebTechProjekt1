<?php
    class DisplayCommentClass{
        public $username;
        public $pfp;
        public $zeitstempel;
        public $text;
        public $uid;
        public $kid;

        public function __construct($username, $pfp, $zeitstempel, $text, $uid, $kid)
        {
            $this->username = $username;
            $this->pfp = $pfp;
            $this->zeitstempel = $zeitstempel;
            $this->text = $text;
            $this->uid = $uid;
            $this->kid = $kid;
        }
    }
?>