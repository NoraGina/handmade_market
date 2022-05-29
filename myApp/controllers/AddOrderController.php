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
            $newOrder = new OrdersModel;
            $newOrderItems = new OrderItemsModel;
            $items = $_SESSION['cart'];
            $loggedInUser = $_SESSION['user'];
            $customer = $newUser->getOne($loggedInUser);
            $customerId = $customer['id'];//userId
            
            $customerAddres = $shipingAddress->getShippingAddressByUserId($customerId);
            
            $firstRow = $items[0];
            $firstKey = $firstRow['id'];
            //FORM DATA
            $addressId = $customerAddres['id'];//shippingAddressId
            $storeId =(int)$firstRow['storeId'];//storeId
            
            $productId = $_POST['productId'];
            $quantity =$_POST['quantity'];
            $suggestions = $_POST['suggestions'];
           //$status = 'AFFECTED';
           //$suggestions = "Nu am";
            //query insert order
            $orderA=$newOrder->addOrder($customerId, $addressId,  $suggestions, $storeId);
            $lastOrder = $newOrder->getLastOrder($customerId);
            $orderId = $lastOrder['id'];
            //$newOrderItems->addOrderItems($items);
            $orderItems = array();
            foreach($items as $item){
                $productId = $item['id'];
                $quantity = $item['quantity'];
                $orderItems= $newOrderItems->addOrderItem($productId, $quantity, $orderId);
                // echo"<br>";
                // echo $productId;
                // echo"<br>";
            }
            if($orderA && $orderItems){
               echo "<h2 class='fst-italic text-success text-uppercase'>  $loggedInUser Comanda a fost plasată </h2>";
               header("Refresh:2; url=home");
            }
            echo "Store";
            echo'<br>';
            echo $storeId;
            echo'<br>';
            echo "User id";
            echo'<br>';
            echo $customerId;
            echo'<br>';
            echo "Shipping address id";
            echo'<br>';
            echo $addressId;
            // echo "Order id";
            // echo'<br>';
            // echo $orderId;
        //    echo'<br>';
        //  echo "User Id";
        //  echo $loggedInUser;
        //  echo'<br>';
        //  echo "Items";
        //      echo'<pre>';
        //      var_dump($items);
        //      echo'</pre>';
        }
    }
}