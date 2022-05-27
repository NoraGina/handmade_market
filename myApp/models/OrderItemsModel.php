<?php
class OrderItemsModel extends DBModel
{
    protected $productId;
    protected $quantity;
    protected $orderId;

    public function __construct($iProductId=1, $iquantity=1, $iOrderId=1){
        $this -> productId = $iProductId;
        $this -> quantity = $iQuantity;
        $this -> orderId = $iOrderId;

    }
}