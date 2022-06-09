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
           
             $items =$_SESSION['cart'];
            
           
            foreach($items as $key=>$value){
                $quantity= $_POST['itemQuantity'][$key];
                $_SESSION['cart'][$key]['itemQuantity']=(int)$quantity;
                
                
               }
                

            
            //  echo"<pre>";
            //  echo var_dump($_SESSION['cart']);
            //  echo"</pre>";
          //  header("Refresh:7;url=cart");
            header("Location:cart");
        }
    }

    

    
}