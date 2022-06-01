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
             echo'<pre>';
             var_dump($items);
             echo'</pre>';
             echo'<br>';
             echo'Keys';
             print_r(array_keys($items));
             $index = $_GET['intKey'];
             // $index = $_GET['key'];
              echo"Index";
              echo $index;
           
            
            // Remove product from cart, check for the URL param "remove", this is the product id, make sure it's a number and check if it's in the cart
            // unset($arr['1'])
                unset($_SESSION['cart'][$_GET['deleteItem']]);

                 header("Refresh:5; url=cart");
            }else{
                echo"Something";
            }
        


    }
}