<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
class SignUpController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        //FORM DATA
        $uFullname = $_POST['signupfullName'];
        $uUsername = $_POST['signupUsername'];
        $uEmail = $_POST['signupEmail'];
        $uPass = $_POST['signupPassword1'];
        $uRole = $_POST['role'];
        $hash = password_hash($uPass, PASSWORD_DEFAULT);
        //INSTANTIATING A NEW USER
        $user = new UsersModel;
        $product = new ProductsModel;
        $products = $product->getAllProducts();
        $category = new CategoriesModel;
        $categories = $category->getAllCategories();
        if($user->addUser($uFullname, $uUsername, $uEmail,$uPass, $hash, $uRole)){
            //IF THE USER HAS BEEN REGISTERED START SESSION
            session_start();
            $_SESSION['user']=$uRole;
            $role=$_SESSION['user'];
           
            //IF USER ROLE == ADMIN REDIRECT TO ADMIN DASHBOARD
            if($role == 'ADMIN'){
                $_SESSION['user'] = $uUsername;
                $loggedInUserAdmin = $_SESSION['user'];
                $data['title'] = 'Admin PAGE';
                $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>Welcome  $loggedInUserAdmin  </h2>";
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addStoreView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
            }else{
                //IF USER ROLE== CUSTOMER REDIRECT TO
                $_SESSION['cart'] = $uUsername;
                $_SESSION['user'] = $uUsername;
                $loggedInUser = $_SESSION['user'];
                
                $data['title'] = ' Customer Home PAGE';
               
                $data['navList'] = $this->bindLinkItems($categories);
                $data['cards'] = $this->showProducts($products);
               
                $data['mainContent'] = "<h2 class='fst-italic text-success text-uppercase'>Welcome   $loggedInUser  </h2>";
                $data['mainContent'].= $this->render(APP_PATH.VIEWS.'addShippingAddressView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
            }
        }else{
            //ELSE REDIRECT TO HOME PAGE
            $data['title'] = 'Home PAGE';
            $data['cards'] = $this->showProducts($products);
            $data['navList'] = $this->bindLinkItems($categories);
            $data['mainContent'] ="<h2 class='fst-italic text-danger'>Username already associated with another account. Please try with diffrent username.' </h2>";
            $data['mainContent'].= $this->render(APP_PATH.VIEWS.'mainHomeView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html', $data);
        }


    }

    public function bindLinkItems($categories){
       
        $output = "";
       if(is_array($categories)){
        foreach($categories as $category){
            $id = $category['id'];
            $name = $category['name'];
            //$stringLink =$this->urlString($name);
            
                $output .="<li class='nav-item'>".
         "<a class='nav-link active' aria-current='page'  href='/TestHandMade/filtredProducts/".$id."'>".$name."</a>".
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
             $output .="<div class='col'>".
                       "<button type='button' name='$id' data-target='myModal$id' class=' open-modal' onclick='openProductModal()'>".
                       "Detalii"."</button>".
                      "</div>";
             $output .="<div class='col'>".
                        "<form action='addToCart' method='POST'>".
                        "<input type='hidden' name='quantity' value='1' min='1' max='".$productQuantity."' >".
                        "<input type='hidden' name='productId' value='".$id."'>".
                       "<input type='submit' class='btn order-btn' value='Comandă'>".
                      
                      "</form>".
                      "</div>";
    
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
                     $output .="<div class='requirements-area'>".
                              "<label>"."Cerințe suplimentare:"."</label>".
                              "<textarea name='otherFacilities' id='' cols='40' rows='3'>".
                              "</textarea>".
                               "</div>";
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
