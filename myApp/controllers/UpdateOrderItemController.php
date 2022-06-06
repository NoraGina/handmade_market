<?php
class UpdateOrderItemController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
       // echo __FILE__;
        session_start();
        if (isset($_SESSION['user']) && isset($_SESSION['cart']) ){
            $productId = (int)$_POST['id'];
            $id = (int)$_POST['id'];
            $name = $_POST['name'];
            $price = doubleval($_POST['price']);
            $quantity = (int)$_POST['itemQuantity'];
            
            
            $items =$_SESSION['cart'];
            $_SESSION['cart'] =$items;
            
            foreach($items as $key=>$value){
                $_SESSION['cart'][$key]['itemQuantity']=$quantity;

            }
            
           // header("Refresh:7;url=cart");
            header("Location:cart");
        }
    }

    

    
}