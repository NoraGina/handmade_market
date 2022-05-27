<?php
class AddToCartController extends AppController
{
    public function __construct(){
        $this->init();
    }

    
    

    public function init(){
        session_start();
        if (isset($_SESSION['user'])  ){
            
            $productId = (int)$_POST['productId'];
            $name = $_POST['name'];
            $price = doubleval($_POST['price']);
            $quantity = (int)$_POST['itemQuantity'];
            $storeId = $_POST['storeId'];
            
           if (isset($_POST['productId'], $_POST['name'], $_POST['price'], $_POST['itemQuantity'])){
                    
                    if(isset($_SESSION['cart']) && is_array ($_SESSION['cart'])){
                           
                        $checkIfStoreAreSame = array_column($_SESSION['cart'], 'storeId');
                        if( empty($_SESSION['cart']) || (in_array($storeId, $checkIfStoreAreSame) &&  !empty($_SESSION['cart']))){
                            $_SESSION['cart'][] = array('id'=>$productId,
                            'name'=>$name,
                            'price'=>$price,
                            'quantity'=>$quantity,
                            'storeId'=>$storeId);
                            
                        }else{
                            echo '<script type="text/javascript">'; 
                            echo 'alert("Poți cumpăra produse livrate de același magazin")' ;
                            echo 'window.location = "home";';
                            echo '</script>';
                     
                        }  
                        
                      
                      
                                      echo"<pre>";
                                      var_dump($_SESSION['cart']);
                                      echo"</pre>"; 
                   }
                         // Prevent form resubmission...
                         header("Refresh:6; url=home"); 
                        
                     
                      
                     
                 }//end if isset$_POST
           
            }else{
                echo '<script type="text/javascript">'; 
                    echo 'alert("Trebuie să ai un cont");'; 
                    echo 'window.location = "home";';
                    echo '</script>';
            }
                
             
        }//init
}//class

/*if(isset($_SESSION['cart']) && is_array ($_SESSION['cart'])){
                           
                            
                            
                              $_SESSION['cart'][] = array('id'=>$productId,
                                                       'name'=>$name,
                                                       'price'=>$price,
                                                       'quantity'=>$quantity,
                                                       'storeId'=>$storeId
                                                );
                            
                            
                                            echo"<pre>";
                                            var_dump($_SESSION['cart']);
                                            echo"</pre>"; 
                         }
                         */