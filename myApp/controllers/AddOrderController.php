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
            echo $customerId;
            $customerAddres = $shipingAddress->getShippingAddressByUserId($customerId);
            
            $firstRow = $items[0];
            $firstKey = $firstRow['id'];
            //FORM DATA
            $addressId = $customerAddres['id'];//shippingAddressId
            $storeId =(int)$firstRow['storeId'];//storeId
            
            $productId = $_POST['productId'];
            $quantity =$_POST['itemQuantity'];
            $suggestions = $_POST['suggestions'];
           
            //query insert order
            $orderA = $ordersModel->addOrder($customerId, $addressId,  $suggestions, $storeId);
            //GET LAST CUSTOMER ORDER
             $lastOrder = $ordersModel->getLastOrder($customerId);
             $orderId = $lastOrder['id'];//orderIs
             //ORDER ITEMS INSERT
             $total = 0;
             foreach($items as $item){
                $productId = $item['id'];
               $quantity = $item['quantity'];
               $total += $item['quantity']*$item['price'];
               $orderItems= $orderItemsModel->addOrderItem($productId, $quantity, $orderId);
               
             }
            //Update product
            $productsOrderItems = $orderItemsModel->getOrderItemsAndProducts($orderId);
            foreach($productsOrderItems as $item){
                $productId = $item['productId'];
                $quantity = floatval($item['quantity']);
                $itemQuantity = floatval($item['itemQuantity']);
                $type = $item['type'];
                if($type == "Pe stoc"){
                    $productQuantity = $quantity - $itemQuantity;
                    $productModel->updateQuantity($productQuantity, $productId);
                }
                
            }
                  
             if($orderA  && $productsOrderItems){
                echo "<h2 class='fst-italic text-success text-uppercase'>  $loggedInUser Comanda în valoare de $total a fost plasată </h2>";
                header("Refresh:3 ; url=home");
             }
            
           
        }
    }
}