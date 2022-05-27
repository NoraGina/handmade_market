<?php

class CartController extends AppController{
    public function __construct(){
        $this->init();
    }

    public function init(){
        echo __FILE__;
        session_start();
        if( isset($_SESSION['user']) && isset($_SESSION['cart'])){
        
            $items = $_SESSION['cart'];
            
           $loggedInUser = $_SESSION['user'];
            $user = new UsersModel;
            $customer = $user->getOne($loggedInUser);
            $userId = $customer['id'];
            // echo'<br>';
            // echo "User Id";
            // echo $userId;
             $address = new ShippingAddressModel;
             $addressCart = $address->getShippingAddressByUserId($userId);
            // echo'<pre>';
            // echo "Items";
            // echo"<br>";
            echo "Items";
             echo'<pre>';
             var_dump($items);
             echo'</pre>';
            
              $firstRow = $items[0];
              $firstKey = $firstRow['id'];
              $storeCart =$firstRow['storeId'];
              echo"Store";
              echo"<br>";
              echo $storeCart;
            //  echo"First key";
            //  echo $firstKey;
            //  echo'<br>';
             $productStore = new ProductsModel;
             $storeCart = $productStore->findProductById($firstKey);
          
            
             $data['title']="Cart Page";
             $category = new CategoriesModel;
             $data['navList'] = $this->bindLinkItems();
             $data['inputs'] = $this->outputCart( $customer, $addressCart,$storeCart );
             $data['table']=$this->table($items);
             $data['mainContent'] = $this->render(APP_PATH.VIEWS.'cartView.html', $data);
             echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
        }
    }

    public function bindLinkItems(){
        $category = new CategoriesModel;
        $categories = $category->getAllCategories();
        $output = "";
       if(is_array($categories)){
        foreach($categories as $category){
            $id = $category['id'];
            $name = $category['name'];
          
            
                $output .="<li class='nav-item'>".
         "<a class='nav-link active' aria-current='page' href='/HandMadeMarket/filetredProducts/".$id."'>".$name."</a>".
          " </li>";
            
           
        }
        return $output;
       }
    }

    public function outputCart( $user, $address, $store){
        $output ="";
        if(is_array($user) && is_array($address)  && is_array($store)){

           
            $output .="<div class='input-group mb-1'>
            <span class='input-group-text' id='basic-addon1'>Client</span>
            <input type='text' readonly class='form-control'  aria-label='Username' aria-describedby='basic-addon1' value='".$user['fullName']."'>
            <input type='hidden' class='form-control' aria-label='Username'  value='".$user['id']."' name='userId'>
          </div>";
          $output.="<h4 style='text-align: center'>Adresa</h4>";
          $output .="<div class='input-group mb-1'>
            <span class='input-group-text' id='basic-addon2'>Județ</span>
            <input type='text' class='form-control' readonly aria-label='Username' aria-describedby='basic-addon2' value='".$address['county']."'>
            </div>";
            $output .="<div class='input-group mb-1'>
            <span class='input-group-text' id='basic-addon3'>Oraș</span>
            <input type='text' class='form-control' readonly aria-label='Username' aria-describedby='basic-addon3' value='".$address['city']."'>
            </div>";
            $output .="<div class='input-group mb-1'>
            <span class='input-group-text' id='basic-addon4'>Adresa</span>
            <input type='text' class='form-control' readonly aria-label='Username' aria-describedby='basic-addon4' value='".$address['address']."'>
            </div>";
            $output .="<div class='input-group'>
            <span class='input-group-text'>Alte specificații</span>
            <textarea class='form-control' aria-label='Alte specificații'></textarea>
          </div>";
            

            return $output;
      }    
        
    }

     public function table($items){
        $product = new ProductsModel;
         $output ="";
         $id = 0;
         $name ="";
         if(is_array($items) ){
             $output.="<table class='table table-bordered border-secundary'>";
             $output.="<tr>";
             $output.= "<th> Id</th>";
             $output.= "<th> Nume produs</th>";
              $output.= "<th> Cantitate</th>";
             $output.= "<th> Preț</th>";
              $output.= "<th> Subtotal</th>";
           
        //      $output .="<th> Editează</th>";
        //      $output .="<th> Șterge</th>";
              $output .="</tr>";
              $total =0;
              foreach($items as  $item){
                  $subtotal = $item['quantity']*$item['price'];
                 $total += $subtotal;
                 $id =$item['id'];
                 $output .="<tr>";
                 $output .="<td>"."<input type='text' class='form-control-plaintext' name='productId' value='".$item['id']."' readonly>"."</input>"."</td>";
                 $output .="<td>"."<input type='text' class='form-control-plaintext' name='name' value='".$item['name']."' readonly>"."</input>"."</td>";
                 $output .="<td>"."<input type='text' class='form-control-sm' name='quantity' value='".$item['quantity']."'>"."</input>"."</td>";
                 $output .="<td>"."<input type='text' class='form-control-plaintext' name='price' value='".$item['price']."' readonly>"."</input>"."</td>";
                 $output .= "<td>".$subtotal."</td>";
                 
                 $output.="</tr>";
                
              }
                       
                 }
                       
             $output .="</table>";
             $output .="<h4 class='ms-auto'>"."Total ".$total."</h4>";
             return $output;
                        
                }
             

         
     

    
}

