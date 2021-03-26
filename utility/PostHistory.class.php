<?php

class PostHistory{
    public $pid;
    public $uid;
    public $bid;
    public $zeitstempel;
    public $tags;
    public $status;
    public $text;
    public $titel;

    public function __construct($pid, $uid, $bid, $zeitstempel, $tags, $status, $text, $titel)
    {
        $this->pid = $pid;
        $this->uid = $uid;
        $this->bid = $bid;
        $this->zeitstempel = $zeitstempel;
        $this->tags = $tags;
        $this->status = $status;
        $this->text = $text;
        $this->titel = $titel;
    }
}