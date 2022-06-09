<?php
class UpdateFormAddressController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        session_start();
            $categoriesModel = new CategoriesModel;
            $categories = $categoriesModel->getAllCategories();
            
        if(isset($_SESSION['user']) && isset($_SESSION['cart'])){
            $loggedInUser = $_SESSION['user'];
            $userModel = new UsersModel;
            $user = $userModel->getOne($loggedInUser);
            $userId = $user['id'];
            
            $shippingAddressModel = new ShippingAddressModel;
            $shippingAddress = $shippingAddressModel->getShippingAddressByUserId($userId);
            
                $data['title'] = 'Customer Home PAGE';
                $data['address']=$this->addressLink($user);
                $data['navList'] = $this->bindLinkItems($categories);
                $data['mainContent']="<h2 class='fst-italic text-success text-uppercase'>Hello  $loggedInUser with cart </h2>";
                $data['mainContent'] .= $this->showShippingAddress($user, $shippingAddress);
                echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
            
            
            
           
        }elseif(isset($_SESSION['user'])){
            $loggedInUser = $_SESSION['user'];
            $userModel = new UsersModel;
            $user = $userModel->getOne($loggedInUser);
            $userId = $user['id'];
            $shippingAddressModel = new ShippingAddressModel;
            $shippingAddress = $shippingAddressModel->getShippingAddressByUserId($userId);
            $data['title'] = 'Customer Home PAGE';
            $data['address']=$this->addressLink($user, $shippingAddress);
            $data['navList'] = $this->bindLinkItems($categories);
            $data['mainContent']="<h2 class='fst-italic text-success text-uppercase'>Hello  $loggedInUser  without cart</h2>";
            $data['mainContent'] .= $this->showShippingAddress($user, $shippingAddress);
            echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);

        }
    }

       
    
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
        }

    public function showShippingAddress($user, $address){
       
        $output = "";
        if($user && $address){
            $id = $user['id'];
            $userName = $user['userName'];
           
            $output .="<div class='container'>
                            <form action='updateShippingAddress/".$id."' method='POST' id='updateAddressForm' >
                                <fieldset class='form-group border border-2 border-black-50 rounded-2  p-3'>
                                    <legend> Adresa de livrare $userName</legend>    
                                        <div class='mb-3 row'>
                                            <label for='exampleFormControlInput2' class='col-sm-3 col-form-label'>Județ<span class='text-danger'>*</span></label>
                                            <div class='col-sm-9'>
                                            <input type='text' name='sCounty' class='form-control' value='".$address['county']."' id='exampleFormControlInput2'
                                                required>
                                            </div>
                                        </div>

                                        <div class='mb-3 row'>
                                            <label class='col-sm-3 col-form-label' for='form6Example6'>Oraș<span class='text-danger'>*</span></label>
                                            <div class='col-sm-9'>
                                                <input type='text' name='sCity' value='".$address['city']."' class='form-control' id='form6Example6' required>
                                            </div>
                                        </div>

                                        <div class='mb-3 row'>
                                            <label for='exampleFormControlTextarea2' class='col-sm-3 col-form-label'>Adresa<span class='text-danger'>*</span></label>
                                            <div class='col-sm-9'><textarea class='form-control ' style='overflow: auto' name='sAddress' id='exampleFormControlTextarea2' rows='2'required>".$address['address']."</textarea></div>
                                        </div>
                                
                                        <div class='mb-3 row'>
                                            <label for='exampleFormControlInput4' class='col-sm-3 col-form-label'>Cod poștal<span class='text-danger'>*</span></label>
                                            <div class='col-sm-9'>
                                                <input type='text' name='sZipCode'  value='".$address['zipCode']."' class='form-control' id='exampleFormControlInput2'
                                                    required>
                                            </div>
                                        </div>
                                        <div class='mb-3 row'>
                                            <label for='exampleFormControlInput4' class='col-sm-3 col-form-label'>Telefon<span class='text-danger'>*</span></label>
                                            <div class='col-sm-9'>
                                                <input type='text' name='sPhone' value='".$address['phone']."' class='form-control text-start' id='exampleFormControlInput4' required
                                                    minlength='9' maxlength='10' >
                                            </div>
                                        </div>
                                    <input type='hidden'  value='".$address['id']."'>
                                        <button type='submit' name='updateFormAddress' class='btn btn-primary btn-block  col-12' >Editează
                                        adresa de livrare</button>
                                </fieldset>
                            </form>
                                    
                        </div>";//container
       

        }
        return $output;
    }
}
