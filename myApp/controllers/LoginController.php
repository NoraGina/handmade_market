<?php
class LoginController extends AppController
{
    public function __construct(){
        $this->init();
    }

    

    public function init(){
        //POST FORM
        $uName = $_POST['loginUsername'];
        $uPass = $_POST['loginPassword'];
        //CREATE USER OBJ AND CHECK IS AUTH
        $newUser = new UsersModel;
        $authUser= $newUser->isAuth($uName, $uPass);
        // //get all produscts
        // $product = new ProductsModel;
        // $products = $product->getAllProducts();
        //get all categories for filtered navbar
        $category = new CategoriesModel;
        $categories = $category->getAllCategories();
        $store = new StoresModel;
        if($authUser){
            session_start();
            $_SESSION['user'] = $uName;
            $myUserId =$authUser['id'];
            $myUser = $newUser->getUserById($myUserId);
            //get role from user
            $role = $myUser['role'];
            $userId = $myUser['id'];
            //if role ADMIN
            if($role=='ADMIN'){
                   //GET STORE BY LOGGEDINUSER
                    $storeByUserId = $store->getStoreByUserId($userId);
                    //if user have store
                    if($storeByUserId){
                        $storeName = $storeByUserId['name'];
                        $storeId = $storeByUserId['id'];
                        //I pass the session on store and display Admin home page
                        $_SESSION['store']=$storeName;
                        $_SESSION['storeId']=$storeId;
                        $userStore=$_SESSION['store'];
                        $data['title'] = 'Admin PAGE';
                        $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>Welcome  $userStore  </h2>";
                        $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                        echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                    }else{
                        $data['title'] = 'Admin PAGE';
                        $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>  $userStore nu ai magazin </h2>";
                        $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addStoreView.html', $data);
                        echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                    }
                }else{
                    $shippingAddress = new ShippingAddressModel;
                    $customerShippingAddress = $shippingAddress->getShippingAddressByUserId($userId);
                    if($customerShippingAddress){
                        $_SESSION['cart'] = array();
                        header("Location:home");
                    }else{
                        $data['title'] = 'Customer PAGE';
                        $data['message'] = "<h2 class='fst-italic danger text-uppercase'>  $loggedIn nu ai o adresă de livrare </h2>";
                        $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addShippingAddressView.html', $data);
                        echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
                    }
                }
        }else{
            header("Location:home");
        }
    }
    
    public function bindLinkItems($categories){
       
        $output = "";
       if(is_array($categories)){
        foreach($categories as $category){
            $id = $category['id'];
            $name = $category['name'];
            
            
                $output .="<li class='nav-item'>".
         "<a class='nav-link active' aria-current='page' href='/HandMadeMarket/filtredProducts/".$id."'>".$name."</a>".
          " </li>";
            
           
        }
        return $output;
       }
    }
     
    
}