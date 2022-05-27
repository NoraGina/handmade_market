<?php
class OrdersModel extends DBModel
{
    protected $userId;
    protected $date;
    protected $orderItems;
    protected $status;
    protected $suggestions;
    protected $storeId;

    public function __construct($oUserId=1, $oDate=now(), $oOrderItems=array(), $oStatus='AFFECTED', $oSuggestions="Su", $oStoreId=1){
        $this->userId= $oUserId;
        $this->date= $oDate;
        $this->orderItems= $oOrderItems;
        $this->status= $oStatus;
        $this->suggestions= $oSuggestions;
        $this->storeId= $oStoreId;

    }

}