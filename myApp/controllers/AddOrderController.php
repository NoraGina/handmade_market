
          <style>
  
 body{
          background-color: #536976;
        
     }
     .container{
         margin-top:10px;
         text-align:center;
     }
     h2{
        color: #ccccff;
        font-style: italic;
        text-transform: uppercase;
     }
     h4{
        color: #ccccff;
     }
  
  
 </style>
<?php
class AddOrderController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        session_start();
        if (isset($_SESSION['user']) && isset($_SESSION['cart'])){
  
            $newUser = new UsersModel;
            $shipingAddress = new ShippingAddressModel;
            $ordersModel = new OrdersModel;
            $orderItemsModel = new OrderItemsModel;
            $productModel = new ProductsModel;
            $items = $_SESSION['cart'];
            $loggedInUser = $_SESSION['user'];
            $customer = $newUser->getOne($loggedInUser);
            $customerId = $customer['id'];//userId
 
            $customerAddres = $shipingAddress->getShippingAddressByUserId($customerId);
            
           
            //FORM DATA
            $addressId = $customerAddres['id'];//shippingAddressId
          
            
            $productId = $_POST['id'];
            $quantity =$_POST['itemQuantity'];
            $suggestions = $_POST['suggestions'];
            $storeId = $_POST['storeId'];
           if(isset($_POST['addOrder'])){
                //query insert order
                $orderA = $ordersModel->addOrder($customerId, $addressId,  $suggestions, $storeId);
                //GET LAST CUSTOMER ORDER
                $lastOrder = $ordersModel->getLastOrder($customerId);
                $orderId = $lastOrder['id'];//orderId
                //ORDER ITEMS INSERT
                $total = 0;
                foreach($items as $item){
                    $productId = $item['id'];
                    $quantity = $item['itemQuantity'];
                    $total += $item['itemQuantity']*$item['price'];
                    $orderItems= $orderItemsModel->addOrderItem($productId, $quantity, $orderId);
                
                }
                //Update product
                // $productsOrderItems = $orderItemsModel->getOrderItemsAndProducts($orderId);
                // foreach($productsOrderItems as $item){
                //     $productId = $item['productId'];
                //     $quantity = floatval($item['quantity']);
                //     $itemQuantity = floatval($item['itemQuantity']);
                //     $type = $item['type'];
                //     if($type == "Pe stoc"){
                //     $productQuantity = $quantity - $itemQuantity;
                //     $productModel->updateQuantity($productQuantity, $productId);
                //     }
                
                //}
           }
            
                  
             if($orderA ){
                 echo"<div class='container'>
                 <h2 class='fst-italic text-light  text-uppercase'> Comanda ta, &nbsp; $loggedInUser &nbsp; în valoare de &nbsp;$total Lei a fost înregistrată </h2>
                <h4 class='fst-italic text-light text-center '> Vă mulțumim că ați comandat la noi, vă vom contacta prin e-mail cu detaliile comenzii dvs. </h2>
                </div>";
                header("Refresh:5 ; url=home");
               
               $_SESSION['cart']= array();
             }
            
           
        }
    }
}