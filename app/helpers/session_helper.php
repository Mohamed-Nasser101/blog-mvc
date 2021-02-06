<?php
session_start();

function flash($name, $msg = null ,$class = "alert alert-success"){
    if (!empty($msg)){
        unset( $_SESSION['flash_name']);
        unset($_SESSION['flash_msg']);
        $_SESSION['flash_name'] = $name;
        $_SESSION['flash_msg'] = $msg;
    }elseif(isset($_SESSION['flash_msg']) && isset($_SESSION['flash_name'])) {
        if($name = $_SESSION['flash_name']){
        echo "<div class='alert alert-success' id='msg-flash'>{$_SESSION['flash_msg']}</div>";
            unset( $_SESSION['flash_name']);
            unset($_SESSION['flash_msg']);
        }
    }
}
function isLogged(){
    if (isset($_SESSION['user_id'])){
        return true;
    }else{
        return  false;
    }
}