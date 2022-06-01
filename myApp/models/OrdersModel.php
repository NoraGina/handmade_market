<?php
class OrdersModel extends DBModel
{
    
    protected $date;
    protected $userId;
   protected $addressId;
    protected $status;
    protected $suggestions;
    protected $storeId;

    public function __construct( $oDate="28", $oUserId=1,$oAddressId=2, $oStatus='AF', $oSuggestions="Su", $oStoreId=1){
       
        $this->date= $oDate;
        $this->userId= $oUserId;
        $this ->addressId=$oAddressId;
        $this->status= $oStatus;
        $this->suggestions= $oSuggestions;
        $this->storeId= $oStoreId;

    }

      // public function addOrder($user, $address, $status, $suggestions, $storeId){
      //   $status = 'AFFECTED';
      //  $sql = "INSERT INTO `orders`(`date`,`customerId`, `addressId`,`status`, `suggestions`,  `storeId`) VALUES (now(), $user, $address, '$status', '$suggestions', $storeId);";
      //    $result = $this->db()->query($sql);
      //  if ($result === TRUE) {
      //        $last_id = $this->db()-> insert_id;
      //        echo "New record created successfully. Last inserted ID is: " . $last_id;
      //      } else {
      //        echo "Error: " . $sql . "<br>" . $this->db()->error;
      //     }
         
    //     // return $this->db()-> insert_id;
    //  }
    

    //  public function addOrder($user, $address, $suggestions, $storeId){
    //      $sql = "INSERT INTO `orders`(`date`,`customerId`, `addressId`,`status`, `suggestions`,  `storeId`) VALUES ((DATE(now)), ?, ?, ?, ?, ?);";
    //      $myPrep = $this->db()->prepare($sql);
    //      $status = 'AFFECTED';
    //    $myPrep->bind_param(iissi, $user, $address, $status, $suggestions, $storeId);
    //    return $myPrep->execute();
             
    //       }
    public function addOrder($user, $address,  $suggestions, $storeId){
      $status = 'AFFECTED';
      $sql = "INSERT INTO `orders`(`date`,`customerId`, `addressId`,`status`, `suggestions`,  `storeId`) 
      VALUES (now(), $user, $address, '$status', '$suggestions', $storeId);";
      $result = $this->db()->query($sql);
      return $result;
    }
  
public function getLastOrder($customerId){
  $sql = "SELECT * FROM `orders` WHERE `customerId`=$customerId ORDER BY id DESC LIMIT 1;";
  $result = $this->db()->query($sql);
  return $result->fetch_assoc();
}

public function getAllOrdersByStoreId($storeId){
  $sql ="SELECT `orders`.`id`, `orders`.`date`, `orders`.`status`, 
  `orders`.`suggestions`, `users`.`fullName`, `users`.`email`, 
  `shipping_address`.`county`, `shipping_address`.`city`, 
  `shipping_address`.`address`, `shipping_address`.`zipCode`, 
  `shipping_address`.`phone`, `order_items`.`itemQuantity`,
   `products`.`name`, `products`.`price` FROM `orders` 
   INNER JOIN `users` ON `orders`.`customerId`=`users`.`id` 
   INNER JOIN `shipping_address` ON `orders`.`addressId`= `shipping_address`.`id` 
   INNER JOIN `order_items` ON `order_items`.`orderId`=`orders`.`id` 
   INNER JOIN `products` ON `order_items`.`productId` = `products`.`id` 
   WHERE `orders`.`storeId` = $storeId;";
   $result = $this->db()->query($sql)->fetch_all(MYSQLI_ASSOC);
   return $result;
}

//update status
  public function updateStatus($status){
    $id = $_GET['id'];
    $sql ="UPDATE `orders` SET `status`= ? WHERE `orders`.`id` = $id;";
    $myPrep = $this->db()->prepare($sql);
    
    $myPrep->bind_param("s",$status );
    return $myPrep->execute();
    
  }

}