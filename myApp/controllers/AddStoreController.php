<?php
class AddStoreController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        echo __FILE__;
         session_start();
         if(isset($_SESSION['user'])){
            $loggedInUserAdmin=$_SESSION['user'];
            $user = new UsersModel;
            //I take the user by the method getOne 
            //that has as parameter the name of the user in the session
            $logUser=$user->getOne($loggedInUserAdmin);
            //I take the user id 
            $userId=$logUser['id'];

        
            //FORM DATA
            $sName = $_POST['name'];
            $sDeliveryTax = $_POST['deliveryTax'];
            $sUserId = $userId;
            $sOtherFacilities = $_POST['otherFacilities'];
            $sCity = $_POST['city'];
            $sAddress = $_POST['address'];
            $sZipCode = $_POST['zipCode'];
            $sPhone = $_POST['phone'];
            $store = new StoresModel;
            
            //if everything went well
            if($store->addStore($sName,$sDeliveryTax, $sUserId,$sOtherFacilities, $sCity, $sAddress, $sZipCode, $sPhone )){
                //I pass the ssesion on store
                //Location is adminView with a success message
                $_SESSION['store']=$sName;
                $sessionStore = $_SESSION['store'];
                $data['title'] = 'Admin PAGE';
                $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>Hello  $sessionStore  </h2>";
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                 echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
            }else{
                //Location is adminView without success  message
                $data['title'] = 'Admin PAGE';
                $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>Ceva nu a mers bine!  </h2>";
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
            }
        }else{
                $data['title'] = 'Home PAGE';
                $data['mainContent'] = "<h2 class='fst-italic text-danger'>Something went wrong  </h2>";
                $data['mainContent'].= $this->render(APP_PATH.VIEWS.'mainHomeView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html',$data);
            }
          
    }
}