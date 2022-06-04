<?php
class AddToCartController extends AppController
{
    public function __construct(){
        $this->init();
    }

    
    

    public function init(){
        session_start();
        if (isset($_SESSION['user'])  ){
            //FORM DATA
            $productId = (int)$_POST['productId'];
            $name = $_POST['name'];
            $price = doubleval($_POST['price']);
            $itemQuantity = (int)$_POST['itemQuantity'];
            $storeId = (int)$_POST['storeId'];
            //Product
            
           if (isset($_POST['productId'], $_POST['name'], $_POST['price'], $_POST['itemQuantity'])){
                    
                    if(isset($_SESSION['cart']) && is_array ($_SESSION['cart'])){
                           //check if product is from same store
                        $checkIfStoreAreSame = array_column($_SESSION['cart'], 'storeId');
                        if( empty($_SESSION['cart']) || (in_array($storeId, $checkIfStoreAreSame) &&  !empty($_SESSION['cart']))){
                            //add product into cart
                            $_SESSION['cart'][$productId] = array('id'=>$productId,
                            'name'=>$name,
                            'price'=>$price,
                            'itemQuantity'=>$itemQuantity,
                            'storeId'=>$storeId);
                            //Update product quantity
                            $productModel = new ProductsModel;
                            $product = $productModel->getProductById($productId);
                            
                            
                                if($product['type']== "Pe stoc"){
                                    $pQuantity = $product['quantity']- $itemQuantity;
                                    $productModel->updateQuantity($pQuantity, $productId);
                                }
                            
                            
                        }else{
                            echo '<script type="text/javascript">'; 
                            echo 'alert("Poți cumpăra produse livrate de același magazin")' ;
                            echo 'window.location = "home";';
                            echo '</script>';
                     
                        }  
                        
                      
                      
                                    //   echo"<pre>";
                                    //   var_dump($_SESSION['cart']);
                                    //   echo"</pre>"; 
                   }
                         // Prevent form resubmission...
                         //header("Refresh:2; url=home"); 
                         header("Location:home");
                        
                     
                      
                     
                 }//end if isset$_POST
           
            }else{
                echo '<script type="text/javascript">'; 
                    echo 'alert("Trebuie să ai un cont");'; 
                    echo 'window.location = "home";';
                    echo '</script>';
            }
                
             
        }//init
}//class

