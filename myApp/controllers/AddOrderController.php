
          <style>
 
 body{
          
   
    background-color: lavender;
        
    
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
            
            //$firstRow = $items[0];
           // $firstKey = $firstRow['id'];
            //FORM DATA
            $addressId = $customerAddres['id'];//shippingAddressId
           // $storeId =(int)$firstRow['storeId'];//storeId
            
            $productId = $_POST['id'];
            $quantity =$_POST['itemQuantity'];
            $suggestions = $_POST['suggestions'];
            $storeId = $_POST['storeId'];
           if(isset($_POST['addOrder'])){
                //query insert order
                $orderA = $ordersModel->addOrder($customerId, $addressId,  $suggestions, $storeId);
                //GET LAST CUSTOMER ORDER
                $lastOrder = $ordersModel->getLastOrder($customerId);
                $orderId = $lastOrder['id'];//orderIs
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
                echo "<h2 class='fst-italic text-success text-center text-uppercase'> Comanda ta,  $loggedInUser în valoare de $total Lei a fost înregistrată </h2>";
                echo "<h4 class='fst-italic text-success text-center '> Vă mulțumim că ați comandat la noi, vă vom contacta prin e-mail cu detaliile comenzii dvs. </h2>";
                header("Refresh:5 ; url=home");
               
               $_SESSION['cart']= array();
             }
            
           
        }
    }
}