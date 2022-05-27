<?php
class ShippingAddressModel extends DBModel
{
   
    protected $userId;
    protected $county;
    protected $city;
    protected $address;
    protected $zipCode;
    protected $phone;

    public function __construct( $sUserId=1, $sCounty="N", $sCity='A', $sAddress='S', $sZipCode='4', $sPhone='0'){
       
        $this->userId=$sUserId;
        $this->otherFacilities=$sCounty;
        $this->city=$sCity;
        $this->address=$sAddress;
        $this->zipCode=$sZipCode;
        $this->phone=$sPhone;
    }

     //add shipping address
     public function addShippingAddress($sUserId,$sCounty, $sCity, $sAddress, $sZipCode, $sPhone ){
        
        $q = "INSERT INTO `shipping_address`( `userId`,`county`, `city`, `address`,`zipCode`, `phone`) VALUES (?, ?, ?, ?, ?, ?);";
        // prepared statements
        $myPrep = $this->db()->prepare($q);
        // s - string, i - integer, d - double, b - blob
        
        $myPrep->bind_param("isssss", $sUserId, $sCounty, $sCity, $sAddress, $sZipCode, $sPhone);
       
        return $myPrep->execute();
        
        // $myPrep->close();
    }

    //get Shipping address by user id
    public function getShippingAddressByUserId($userId){
        $sql = "SELECT * FROM `shipping_address` WHERE `userId` = ? ; ";
        $myPrep = $this->db()->prepare($sql);
        $myPrep->bind_param("i", $userId);
        $myPrep->execute();
        $result = $myPrep->get_result();
        return $result->fetch_assoc();
    }
}