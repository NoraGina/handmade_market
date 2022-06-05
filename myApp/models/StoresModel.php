<?php
class StoresModel extends DBModel
{
    protected $name;
    protected $deliveryTax;
    protected $userId;
    protected $otherFacilities;
    protected $city;
    protected $address;
    protected $zipCode;
    protected $phone;

    public function __construct($sName='S', $sDeliveryTax=1, $sUserId=1, $sOtherFacilities="N", $sCity='A', $sAddress='S', $sZipCode='4', $sPhone='0'){
        $this->name=$sName;
        $this->deliveryTax=$sDeliveryTax;
        $this->userId=$sUserId;
        $this->otherFacilities=$sOtherFacilities;
        $this->city=$sCity;
        $this->address=$sAddress;
        $this->zipCode=$sZipCode;
        $this->phone=$sPhone;
    }

    //add store
    public function addStore($sName,$sDeliveryTax, $sUserId,$otherFacilities, $sCity, $sAddress, $sZipCode, $sPhone ){
        
        $q = "INSERT INTO `stores`(`name`,`deliveryTax`, `userId`,`otherFacilities`, `city`, `address`,`zipCode`, `phone`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
        // prepared statements
        $myPrep = $this->db()->prepare($q);
        // s - string, i - integer, d - double, b - blob
        
        $myPrep->bind_param("ssisssss",$sName, $sDeliveryTax, $sUserId, $otherFacilities, $sCity, $sAddress, $sZipCode, $sPhone);
       
        return $myPrep->execute();
        
        // $myPrep->close();
    }

    //function get one store by userId 
    public function getStoreByUserId($uId){
        $sql = "SELECT * FROM `stores` WHERE `userId` = ? ; ";
        $myPrep = $this->db()->prepare($sql);
        $myPrep->bind_param("i", $uId);
        $myPrep->execute();
        $result = $myPrep->get_result();
        return $result->fetch_assoc();
    }

    
    //function get one store by name
    public function getStoreByName($sName){
        $sql = "SELECT * FROM `stores` WHERE `name` = ? ; ";
        $myPrep = $this->db()->prepare($sql);
        $myPrep->bind_param("s", $sName);
        $myPrep->execute();
        $result = $myPrep->get_result();
        return $result->fetch_assoc();
    }
     //function get one store by id 
     public function getStoreById($sId){
        $sql = "SELECT * FROM `stores` WHERE `id` = ? ; ";
        $myPrep = $this->db()->prepare($sql);
        $myPrep->bind_param("i", $sId);
        $myPrep->execute();
        $result = $myPrep->get_result();
        if($result){
            return $result->fetch_assoc();
        }
       
    }

    //get all stores
    public function getAllStores(){
        $sql = "SELECT * FROM `stores`;";
        $result = $this->db()->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}