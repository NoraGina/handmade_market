<?php
class LogoutController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        echo __FILE__;
        session_start();
        session_destroy();
        header("Location: home");
    }
}