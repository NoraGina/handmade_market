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
        //get all produscts
        $product = new ProductsModel;
        $products = $product->getAllProducts();
        //get all categories for filtered navbar
        $category = new CategoriesModel;
        $categories = $category->getAllCategories();
        $store = new StoresModel;
        if($authUser){
            session_start();
            $_SESSION['user'] = $uName;
            
            $_SESSION['cart'] =$uName;
           
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
    // public function init1(){
    //     //POST FORM
    //     $uName = $_POST['loginUsername'];
    //     $uPass = $_POST['loginPassword'];
    //     //CREATE USER OBJ AND CHECK IS AUTH
    //     $newUser = new UsersModel;
    //     $authUser= $newUser->isAuth($uName, $uPass);
    //     //get all produscts
    //     $product = new ProductsModel;
    //     $products = $product->getAllProducts();
    //     //get all categories for filtered navbar
    //     $category = new CategoriesModel;
    //     $categories = $category->getAllCategories();
    //     if($authUser){
    //                 session_start();
    //                 $_SESSION['user'] = $uName;
    //                 $_SESSION['cart'] =$uName;
    //                 $loggedIn = $_SESSION['user'];
    //                 //get role from user
    //                 $role = $authUser['role'];
    //                 $userId = $authUser['id'];
    //                 //if role ADMIN
    //                 if($role=='ADMIN'){
    //                     $store = new StoresModel;
    //                     //GET STORE BY LOGGEDINUSER
    //                     $storeByUserId = $store->getStoreByUserId($userId);
    //                     //if user have store
    //                     if($storeByUserId){
    //                         $storeName = $storeByUserId['name'];
                    
    //                             $storeId = $storeByUserId['id'];
    //                         //I pass the session on store and display Admin home page
    //                             $_SESSION['store']=$storeName;
    //                             $_SESSION['storeId']=$storeId;
    //                             $userStore=$_SESSION['store'];
    //                             $data['title'] = 'Admin PAGE';
    //                             $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>Welcome  $userStore  </h2>";
    //                             $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
    //                             echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
    //                             //else redirect to add store
    //                         }else{
                                
    //                             header('Location:adminView');
    //                         }// END ADMIN
    //                     //IF USER ROLE== CUSTOMER REDIRECT TO 
    //                 }else {
                        
    //                     //if products
    //                     if(!empty($products)){
    //                     $data['title'] = 'Customer PAGE';
    //                     header('Location:home');
    //                     }else{
    //                         $data['cards'] = "<h2 class='fst-italic text-danger '>Nu sunt produse  </h2>";
    //                         $data['mainContent']="<h2 class='fst-italic text-success text-uppercase'>Încă  </h2>";
    //                         echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
                            
    //                     }
    //                 }  //END ELSE CUSTOMER 
    //              }else{
                
    //      }else{
    //         header('Location:home');
    //      }

    // }
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
     
    //function to show products
    public function showProducts($products){
  
        $output = "";
        if(is_array($products)){
        
         foreach($products as $product){
             //Taking each  product id
             $id = $product['id'];
             $productQuantity = $product['quantity'];
             $storeId = $product['storeId'];
            //query get stores by storeId
            $store = new StoresModel;
            $storeProduct =$store->getStoreById($storeId);
            
            $subst =substr($product['description'],0,40);
             $text =substr($product['description'],40,strlen($product['description']));
    
             //variable for display qunatity as string
             $str ="";
             //if quantity > 0 will display in stoc
             if($product['quantity']>0){
                 $str="Produs pe stoc";
             }else{
                 $str="Produs la comandă";
             }
            
           if(is_array($storeProduct)){
                 //variable for delivery tax
             $delivery = "";
             if($storeProduct['deliveryTax']='Nu achit taxa de transport'){
                 $delivery ="Produsul nu include taxă de transport";
             }else{
                $delivery ="Produsul  include taxă de transport";
             }
            
            // $output .="<a href=''>";
           
             $output .=" <div class='col-10  col-sm-6 col-md-4 col-lg-3 col-xl-3 rounded-2'>";
             $output .=" <div class='card h-100 ' style='border:1px solid #ffcc99'>"; 
             $output .="<div class='box-img'>"."<img src='myApp/img/".$product['image']."'class='product-img' alt=".$product['name']."/> "."</div>";//end box-img
             $output .=" <div class='card-body'>";
             $output .="<h5 class='card-title '>".$product['name']."</h5>";
             
             $output .="<p class='fs-6 text-start data-target='subText$id''>".$subst.
                    "<span class=' read-more-text' id='text$id' >".$text.
                    "</span>"."<span class=' fst-italic read-more-btn more' id='more$id' value= ' Citește mai puțin...' data-target='text$id' onclick='displayAll()' >"." Citește mai mult..."."</span>".
                    "<span class=' fst-italic d-none read-less-btn less' id='less$id'  data-target='text$id' onclick='toggleMoreLess()' >"." Citește mai puțin..."."</span>".
                   "<p>";
            
            
             $output .=" <p class='text-start fs-6'>".$id."</p>";
             $output .="<p class='text-start fs-6'>".$str."</p>";
             $output .="<p class='text-start fs-6'>".$delivery."</p>";
             $output .=" <p class='text-start  fs-6'>"."Preț "."<span>".$product['price']."</span>"." Lei "."</p>";
             $output .=" <div class='row '>";
             
             $output .=
                        "<form action='addToCart' method='POST'>".
                        "<input type='number' name='quantity' value='1' min='1' max='".$productQuantity."' >".
                        "<input type='hidden' name='productId' value='".$id."'>".
                       "<input type='submit' class='btn order-btn' value='Comandă'>".
                      
                      "</form>";
            $output .="<button type='button' name='$id' data-target='myModal$id' class=' open-modal' onclick='openProductModal()'>".
                      "Detalii"."</button>";
             $output .="</div>";//end row
             $output .="</div>";//end card body
             $output .="</div>";//end card
             $output .="</div>";//end col
    
             
                     //MODAL
                     $output .="<div class='products-modal' id='myModal$id'>";
                     $output .="<div class='container my-container'>";
                     $output .="<span class='close-modal fs-4'>"."X"."</span>";
                    
                     $output .="<div  class='row '>";
                     $output .="<div class='col'>".
                            "<img class='modal-img' src='myApp/img/".$product['image']."'  alt=".$product['name']."/>".
                            "</div>";
                     $output .="<div class='col'>";
                     $output .="<h3 class='modal-title text-center'>".$product['name']."</h3>";
                     $output .="<p class='fs-6 '>".$product['description']."</p>";
                     $output .="<p class='fs-6 '>"."Livrat de "."<span class='class=fs-6 text fw-bolder'>".$storeProduct['name']."</span>"."</p>";
                     $output .="<p class=' fs-6'>".$delivery."</p>";
                     $output .="<p class='fs-6 '>"."Alte oferte "."<span class='class=fs-6 text fw-bolder'>".$storeProduct['otherFacilities']."</span>"."</p>";
                     $output .="<p class='fs-6'>".$str."</p>";
                     $output .="<p class='fs-6'>"."Preț: "."<span class='class=fs-6 text fw-bolder'>".$product['price']."</span>"."<span class='fs-6 text'>"." Lei"."<span>"."</p>";
                    
                    $output .="<button class='btn by-btn'>"."<i class='bi bi-cart-plus-fill'>"."</i>"."Adaugă în coș"."</button>";
                     
                      
                    $output .="</div>";//end col
                    $output .="</div>";//end row
                   // $output .="</div>";//end content
                    $output .="</div>";//end my-container
                    $output .="</div>";//end products-modal";
         }//foreach
           }
                            
        }//if products array
        return $output;
    }
}