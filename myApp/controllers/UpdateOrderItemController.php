<?php
class UpdateOrderItemController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        echo __FILE__;
        session_start();
        if (isset($_SESSION['user']) && isset($_SESSION['cart']) ){
            $productId = (int)$_POST['id'];
            $id = (int)$_POST['id'];
            $name = $_POST['name'];
            $price = doubleval($_POST['price']);
            $quantity = (int)$_POST['itemQuantity'];
            //$id = $_GET['id'];
            $productModel = new ProductsModel;
            $product = $productModel->getProductById($productId);
            $storeId =$product['storeId'];
            $items =$_SESSION['cart'];
            $_SESSION['cart'] =$items;
            // echo"Items";
            //  echo"<pre>";           
            // echo var_dump($items);
            // echo"</pre>";
            
            $id = (int)$_POST['id'];
            // echo"SESSION";
            // echo"<pre>";
            // var_dump($_SESSION['cart']);
            // echo"</pre>";
            // echo"<br>";
            
            foreach($_SESSION['cart'][$id] as $key=>$value){
            
            $_SESSION['cart'][$id]['itemQuantity']=$_POST['itemQuantity'];
            
            }
            foreach($items as $item){
                $pId = $item['id'];
               
                $productModel = new ProductsModel;
               $product = $productModel->getProductById($pId);
               
                $productQuantity = floatval($product['quantity']);
                $itemQuantity = floatval($item['itemQuantity']);
                $type = $product['type'];
                if($type == "Pe stoc" && $itemQuantity != $quantity){
                $updateQuantity = $productQuantity + $itemQuantity - $quantity;
                $productModel->updateQuantity($updateQuantity, $pId);
                }
            
            }
           
            // echo"SESSION UPDATE";
            //  echo"<pre>";
            // var_dump($_SESSION['cart']);
            // echo"</pre>";
            
           
           
                //header("Refresh:8;url=cart");
                 header("Location:cart");
        }
    }

    function updateOrders($items){
        foreach($items as $item){
            $_SESSION['cart'][$item['id']][$item['itemQuantity']]=$_POST['itemQuantity'];
        }
        return $_SESSION['cart'];
    }

    
}