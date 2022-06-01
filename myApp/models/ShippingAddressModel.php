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

//  public function updateShippingAddress($sCounty, $sCity, $sAddress, $sZipCode, $sPhone){
//     $id= $_GET['id'];
    
//    $sql = "UPDATE `shipping_address` SET   `county` = '$sCounty',`city` = '$sCity', `address` = '$sAddress',  `zipCode` = '$sZipCode', `phone` = '$sPhone' WHERE `shipping_address`.`id` = $id;";
    
//      $result = $this->db()->query($sql);
//      if ($result === TRUE) {
           
//                echo "New record created successfully. ";
//               } else {
//                echo "Error: " . $sql . "<br>" . $this->db()->error;
//              }
//  }

     public function updateShippingAddress($sCounty, $sCity, $sAddress, $sZipCode, $sPhone){
         $id = $_GET['id'];
         $sql="UPDATE `shipping_address` SET `county` = ?, `city` = ?,`address` = ?,`zipCode` = ?,`phone` = ? WHERE `shipping_address`.`id` = $id";
         $myPrep = $this->db()->prepare($sql);
         $myPrep->bind_param("sssss",$sCounty, $sCity, $sAddress, $sZipCode, $sPhone);
         $result =  $myPrep->execute();
         if ($result === TRUE) {
           
                         echo "New record created successfully. ";
                         } else {
                           echo "Error: " . $sql . "<br>" . $this->db()->error;
                         }

            return $result;
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