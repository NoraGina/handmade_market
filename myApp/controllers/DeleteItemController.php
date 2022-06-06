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
            $id = $_GET['id'];
           $items = $_SESSION['cart'];
           //$_SESSION['cart']= $items;
           unset($_SESSION['cart'][$id]);
           //header("Refresh:9;url=../cart");
           header("Location:../cart");
        }else{
                echo"Something";
            }

    }
}
/*if($item == $_SESSION['cart'][$id] && array_key_exists($id,$_SESSION['cart'])){
                        
                    //  echo"Session id" ;
                    //  echo"<pre>";
                    //  var_dump($_SESSION['cart'][$id]);
                    //  echo"</pre>";
                    $productModel = new ProductsModel;
                    $product = $productModel->getProductById($pId);
                if(is_array($product)){
                    $productQuantity = floatval($product['quantity']);
                    $itemQuantity = floatval($item['itemQuantity']);
                    $type = $product['type'];
                    if($type == "Pe stoc"){
                        $updateQuantity =  $productQuantity+$itemQuantity;
                        $productModel->updateQuantity($updateQuantity, $pId);
                    }
                }
      foreach($_SESSION['cart'] as $item){
               if(is_array($_SESSION['cart'])){
                $pId = $item['id'];
                 // echo"<br>";
                // echo"Items";
                // echo"<pre>";
                // var_dump($item);
                // echo"</pre>";
               
                        
               }
            }              
                    
                }*/