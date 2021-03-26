<?php

function printLuki (){
    echo 'Hier könnte Ihre Werbug stehen!';
}

function invalidusername($username){

    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
        $result = true;
    }else {
        $result = false;
    }
    return $result;
}

function invalidName($name){

    if(!preg_match("/^[a-zA-Z]*$/", $name)){
        $result = true;
    }else {
        $result = false;
    }
    return $result;
}

function invalidEmail($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = 'invalidEmail';
    }else {
        $error = '';
    }
    return $error;
}

function pwdMatch($pwd, $pwdRepeat){
    if($pwd !== $pwdRepeat){
        $error = 'pwdNoMatch';
    }else {
        $error = '';
    }
    return $error;
}

function emptyInput($input){
    if (empty($input)) {
        $error = 'emptyInput';
    }else {
        $error = '';
    }
    return $error;
}

function make_thumb($src, $dest, $desired_width) {

    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height * ($desired_width / $width));

    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);
}