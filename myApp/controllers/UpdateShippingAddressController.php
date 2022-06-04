<?php
class UpdateShippingAddressController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        // echo __FILE__;
        session_start();
        if(isset($_SESSION['user']) && isset($_SESSION['cart'])){
            $loggedInUser=$_SESSION['user'];
            $user = new UsersModel;
            $customer = $user->getOne($loggedInUser);
            $userId = $customer['id'];
            
            $shippingAddress = new ShippingAddressModel;
            //FORM DATA
            $sCounty = $_POST['sCounty'];
            $sCity = $_POST['sCity'];
            $sUserId = $userId;
            $sAddress = $_POST['sAddress'];
            $sZipCode = $_POST['sZipCode'];
            $sPhone = $_POST['sPhone'];
            echo"<br>";
            
            $newAddress = $shippingAddress->updateShippingAddress($sCounty, $sCity, $sAddress, $sZipCode, $sPhone);
            if( $newAddress){
                header("Refresh:6; url=../home");
               // header('Location:../home');
            }else{
                echo"Ceva nu a mers bine";
                header("Refresh:6; url=../home");
            }
        }else{
            echo "Something";
        }
    }

     
}