<?php
class OrderItemsModel extends DBModel
{
    protected $productId;
    protected $quantity;
    protected $orderId;

    public function __construct($iProductId=1, $iQuantity=1, $iOrderId=1){
        $this -> productId = $iProductId;
        $this -> quantity = $iQuantity;
        $this -> orderId = $iOrderId;

    }

    // public function addOrderItem($productId, $quantity, $orderId){
    //     $sql = ("INSERT INTO `orders_item`(`productId`, `quantity`, `orderId`)VALUES(?,?,?);");
    //     $myPrep = $this->db()->prepare($sql);
    //     $myPrep->bind_param(iii,$productId, $quantity, $orderId);
    //     return $myPrep->execute();
    // }

//     public function addOrderItems($items){
//         $productId=$
//         foreach($items as $item){
//             $productId=$item['id'];
//             $quantity = $item['quantity'];
//            // $sql .= "INSERT INTO `orders_item`(`productId`,`quantity`, `orderId`) VALUES ($item['productId'], $item['quantity'], 10);";
//              $sql .= "INSERT INTO `orders_item` (productId, quantity, orderId)
//  VALUES ('productId', 'quantity', 10);";
//         }
//         if ($this->db()->multi_query($sql) === TRUE) {
//             echo "New records created successfully";
//           } else {
//             echo "Error: " . $sql . "<br>" . $conn->error;
//           }
//     }

    public function addOrderItem($productId, $quantity, $orderId){
        
        $sql = "INSERT INTO `order_items`(`productId`,`quantity`, `orderId`) 
        VALUES ($productId, $quantity, $orderId);";
        $result = $this->db()->query($sql);
        // if ($result === TRUE) {
        //                 echo "New records created successfully";
        //                } else {
        //                 //echo "Error: " . $sql . "<br>" . $this->db()->error;
        //                 echo("Error description: ". "<br>"  . $this->db() -> error);

        //               }
      
     return  $result;
         
        // return $this->db()-> insert_id;
     }
}