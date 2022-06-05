<?php
class SearchProductController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        session_start();
        
        $product = new ProductsModel;
        //$products = $product->filterProductsByCategoryId($id);
        $category = new CategoriesModel;
        $categories = $category->getAllCategories();
        
        $searchTerm= $_POST['searchTerm'];
         echo $searchTerm;
        // echo '<br>';
        $searchTermToLowerCase = strtolower($searchTerm);
        $searchProd = $product->filterProductsByName($searchTermToLowerCase);
        // echo'<pre>';
        //  var_dump($searchProd);
        //  echo'</pre>';
        if(isset($_SESSION['user']) && isset($_SESSION['cart'])){
            $loggedInUser = $_SESSION['user'];
            $newUser = new UsersModel;
            $user = $newUser->getOne($loggedInUser);
            $userId = $user['id'];
            $address = new ShippingAddressModel;
            $customerAddress = $address->getShippingAddressByUserId($userId);
            if($searchProd){
                $data['title'] = 'Fitered Products  PAGE';
                $data['address']=$this->addressLink($user);
                $data['navList'] = $this->bindLinkItems($categories);
                $data['cards'] = $this->showProductsFiltered($searchProd);
                $data['modals']=$this->modalsFiltered($searchProd);
                $data['mainContent']="<h2 class='fst-italic text-success text-uppercase'>Hello  $loggedInUser  </h2>";
                $data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainHomeView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
            }else{
                $data['title'] = 'Fitered Products  PAGE';
                $data['address']=$this->addressLink($user);
                $data['navList'] = $this->bindLinkItems($categories);
                $data['mainContent']="<h2 class='fst-italic text-danger '>Nu am găsit niciun rezultat </h2>";
                //$data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainHomeView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
            }
         }elseif(isset($_SESSION['user'])){
            if($searchProd){
                $data['title'] = 'Fitered Products  PAGE';
                $data['address']=$this->addressLink($user);
                $data['navList'] = $this->bindLinkItems($categories);
                $data['cards'] = $this->showProductsFiltered($searchProd);
                $data['modals']=$this->modalsFiltered($searchProd);
                $data['mainContent']="<h2 class='fst-italic text-success text-uppercase'>Hello  $loggedInUser  </h2>";
                $data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainHomeView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
            }else{
                $data['title'] = 'Fitered Products  PAGE';
                $data['address']=$this->addressLink($user);
                $data['navList'] = $this->bindLinkItems($categories);
                $data['mainContent']="<h2 class='fst-italic text-danger '>Nu am găsit niciun rezultat </h2>";
                //$data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainHomeView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
            }

        }else{
            if($searchProd){
                $data['title'] = 'Fitered Products  PAGE';
                
                $data['navList'] = $this->bindLinkItems($categories);
                $data['modals'] = $this->modalsFiltered($searchProd);
                $data['cards'] = $this->showProductsPublicFiltered($searchProd);
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainHomeView.html', $data);
        
                echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html',$data);
            }else{
                $data['title'] = 'Fitered Products  PAGE';
                $data['address']=$this->addressLink($user);
                $data['navList'] = $this->bindLinkItems($categories);
                $data['mainContent']="<h2 class='fst-italic text-danger '>Nu am găsit niciun rezultat </h2>";
                $data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainHomeView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html',$data);
            }
        }
        
    }

    public function showProductsFiltered($products){
    
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
                if($product['type']=="Pe stoc"){
                    //if quantity > 0 will display in stoc
                if($product['quantity']>0){
                    $str="Produse pe stoc:  ".$product['quantity'];
                }else{
                    $str="Produs indisponibil";
                }
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
                }
                $output .=" <div class='col  '>";
                //$output .=" <div class='col-10  col-sm-6 col-md-4 col-lg-3 col-xl-3 rounded-2'>";
                $output .= "<div class='row d-flex justify-content-evenly align-items-start '>";
                $output .=" <div class='card h-100'  id='cardId'>"; 
                $output .="<div class='box-img'>"."<img src='myApp/img/".$product['image']."'class='product-img' alt=".$product['name']."/> "."</div>";//end box-img
                $output .=" <div class='card-body'>";
                $output .="<h5 class='card-title '>".$product['name']."</h5>";
                
                $output .="<p class='fs-6 text-start lh-1 data-target='subText$id''>".$subst.
                        "<span class=' read-more-text' id='text$id' >".$text.
                        "</span>"."<span class=' fst-italic read-more-btn more' id='more$id' value= ' Citește mai puțin...' data-target='text$id' onclick='displayAll()' >"." Citește mai mult..."."</span>".
                        "<p>";
                $output .=" <p class='text-start fs-6 lh-1'>"."Produs livrat de  ".$storeProduct['name']."</p>";
                $output .="<p class='text-start fs-6 lh-1'>".$str."</p>";
                $output .="<p class='text-start fs-6 lh-1'>".$delivery."</p>";
                $output .=" <p class='text-start  fs-6 lh-1'>"."Preț "."<span>".$product['price']."</span>"." Lei "."</p>";
                // $output .=" <div class='row '>";
            
                $output .="<div class='row'>";
                $output .=" <div class='col align-self-end'>";
                $output.=  "<form action='addToCart' method='POST'>".
                            "<input type='number' name='itemQuantity' style='width:100%' value='".$productQuantity."' min='1' max='".$productQuantity."' >".
                            "<input type='hidden' name='productId' value='".$id."'>".
                            "<input type='hidden' name='storeId' value='".$storeId."'>".
                            "<input type='hidden' name='name' value='".$product['name']."'>".
                            "<input type='hidden' name='price' value='".$product['price']."'>".
                        "<input type='submit' name='addToCart' class='btn order-btn' value='Comandă'>".
                        
                        "</form>";
                $output .="</div>";
                $output .="</div>";
                $output .="<div class='row'>";
                $output .=" <div class='col align-self-end'>";
                $output.=  "<button type='button' name='$id' data-target='myModal$id' class='btn open-modal ' onclick='openProductModal()'>".
                        "Detalii"."</button>";
                $output .="</div>";//end row
                $output .="</div>";//end col

                $output .="</div>";//end card body
                $output .="</div>";//end card
                $output .="</div>";//end row
                $output .="</div>";//end col
                    
            }//foreach
        
            
        
        }//if products array
        return $output;
    }

    //function to show products public page
    public function showProductsPublicFiltered($products){
    
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
                if($product['type']=="Pe stoc"){
                    //if quantity > 0 will display in stoc
                if($product['quantity']>0){
                    $str="Produse pe stoc:  ".$product['quantity'];
                }else{
                    $str="Produs indisponibil";
                }
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
                }
                $output .=" <div class='col  '>";
                //$output .=" <div class='col-10  col-sm-6 col-md-4 col-lg-3 col-xl-3 rounded-2'>";
                $output .= "<div class='row d-flex justify-content-evenly align-items-start rounded-2'>";
                $output .=" <div class='card h-100 ' style='border:1px solid #ffcc99;width: 19rem;padding:0px'>"; 
                $output .="<div class='box-img'>"."<img src='myApp/img/".$product['image']."'class='product-img' alt=".$product['name']."/> "."</div>";//end box-img
                $output .=" <div class='card-body'>";
                $output .="<h5 class='card-title '>".$product['name']."</h5>";
                
                $output .="<p class='fs-6 text-start lh-1 data-target='subText$id''>".$subst.
                        "<span class=' read-more-text' id='text$id' >".$text.
                        "</span>"."<span class=' fst-italic read-more-btn more' id='more$id' value= ' Citește mai puțin...' data-target='text$id' onclick='displayAll()' >"." Citește mai mult..."."</span>".
                        "<p>";
                $output .=" <p class='text-start fs-6 lh-1'>"."Produs livrat de  ".$storeProduct['name']."</p>";
                $output .="<p class='text-start fs-6 lh-1'>".$str."</p>";
                $output .="<p class='text-start fs-6 lh-1'>".$delivery."</p>";
                $output .=" <p class='text-start  fs-6 lh-1'>"."Preț "."<span>".$product['price']."</span>"." Lei "."</p>";
                // $output .=" <div class='row '>";
            
                $output .="<div class='row'>";
                $output .=" <div class='col align-self-end'>";
                $output.=  "<form action='addToCart' method='POST'>".
                            "<input type='hidden' name='itemQuantity' style='width:100%' value='".$productQuantity."' min='1' max='".$productQuantity."' >".
                            "<input type='hidden' name='productId' value='".$id."'>".
                            "<input type='hidden' name='storeId' value='".$storeId."'>".
                            "<input type='hidden' name='name' value='".$product['name']."'>".
                            "<input type='hidden' name='price' value='".$product['price']."'>".
                        "<input type='submit' name='addToCart' class='btn order-btn' value='Comandă'>".
                        
                        "</form>";
                $output .="</div>";//end col
                $output .=" <div class='col align-self-end'>";
                $output.=  "<button type='button' name='$id' data-target='myModal$id' class='btn open-modal ' onclick='openProductModal()'>".
                        "Detalii"."</button>";
                $output .="</div>";
            
                
                $output .="</div>";//end row
                

                $output .="</div>";//end card body
                $output .="</div>";//end card
                $output .="</div>";//end row
                $output .="</div>";//end col
                    
            }//foreach
        
            
        
        }//if products array
        return $output;
    }

    public function modalsFiltered($products){
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

                //variable for display qunatity as string
                $str ="";
                if($product['type']=="Pe stoc"){
                    //if quantity > 0 will display in stoc
                if($product['quantity']>0){
                    $str="Produse pe stoc:  ".$product['quantity'];
                }else{
                    $str="Produs indisponibil";
                }
                }else{
                    $str="Produs la comandă";
                }//if quantity
                if(is_array($storeProduct)){
                    //variable for delivery tax
                    $delivery = "";
                    if($storeProduct['deliveryTax']='Nu achit taxa de transport'){
                        $delivery ="Produsul nu include taxă de transport";
                    }else{
                    $delivery ="Produsul  include taxă de transport";
                    }
                }//if is_array store
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
                
                $output .=  "<form action='addToCart' method='POST'>".
                                "<input type='hidden' name='itemQuantity' value='1' min='1' max='".$productQuantity."' >".
                                "<input type='hidden' name='productId' value='".$id."'>".
                                "<input type='hidden' name='storeId' value='".$storeId."'>".
                                "<input type='hidden' name='name' value='".$product['name']."'>".
                                "<input type='hidden' name='price' value='".$product['price']."'>".
                                "<button type='submit' name='addToCart' class='btn by-btn'>"."<i class='bi bi-cart-plus-fill'>"."</i>"."Adaugă în coș"."</button>".
                            
                            "</form>";

                $output .="</div>";//end col
                $output .="</div>";//end row
                // $output .="</div>";//end content
                $output .="</div>";//end my-container
                $output .="</div>";//end products-modal";
            }
            
        }//if arrray products
        return $output;
    }//function

    public function bindLinkItems($categories){
        
            $output = "";
        if(is_array($categories)){
            foreach($categories as $category){
                $id = $category['id'];
                $name = $category['name'];
            
                
                    $output .="<li class='nav-item'>".
            "<a class='nav-link active' aria-current='page' href='/HandMadeMarket/filteredProducts/".$id."'>".$name."</a>".
            " </li>";
                
            
            }
            return $output;
         }
    }

    public function addressLink($user){
        $output ="";
        if($user){
            $id = $user['id'];
            $output .="<li class='nav-item'>
            <a class='nav-link' href='updateFormAddress/".$id."' >
            <i class='bi bi-truck'>Adresa de livrare</i>
            </a></li>";
        }
        return $output;
    }
}