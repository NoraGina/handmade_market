<?php
class DeleteItemController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        echo __FILE__;
        session_start();
        if (isset($_SESSION['user']) && isset($_SESSION['cart'])){
           $items = $_SESSION['cart'];
            unset($_SESSION['cart'][$id]);
            header("Location:../cart");
            }else{
                echo"Something";
            }

    }
}