<?php

class CartController extends AppController{
    public function __construct(){
        $this->init();
    }

    public function init(){
        session_start();
        if( isset($_SESSION['user']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
          $category = new CategoriesModel;
          $items = $_SESSION['cart'];
          // echo'<pre>';
          // var_dump($items);
          // echo'</pre>';
          // echo'<br>';
          
            $loggedInUser = $_SESSION['user'];
             $user = new UsersModel;
             $customer = $user->getOne($loggedInUser);
             
             $userId = $customer['id'];
             $address = new ShippingAddressModel;
             $addressCart = $address->getShippingAddressByUserId($userId);
          
              
              
              
              $data['title']="Cart Page";
              $data['address']=$this->addressLink($customer);
              $data['navList'] = $this->bindLinkItems();
              //$data['inputs'] = $this->outputCart( $customer, $addressCart );
              //$data['table']=$this->table($items);
              //$data['mainContent'] = $this->render(APP_PATH.VIEWS.'cartView.html', $data);
              $data['mainContent'] = $this->outputCart( $customer, $addressCart, $items );
              
              echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
          
           
        }else{
          echo "<h2 class='fst-italic text-success text-uppercase'> Nu ai produse în coș </h2>";
          header("Refresh:2; url=home");
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

    public function outputCart( $user, $address, $items){
      $output ="";
      if(is_array($user) && is_array($address) && is_array($items) ){
        $id = $user['id'];
         $output .="<form action='addOrder' method='POST' id='cartForm'>";
          $output .="<div class='input-group mb-1'>
          <span class='input-group-text' id='basic-addon1'>Client</span>
          <input type='text' readonly class='form-control'  aria-label='Username' aria-describedby='basic-addon1' value='".$user['fullName']."'>
          <input type='hidden' class='form-control' aria-label='Username'  value='".$user['id']."' name='userId'>
        </div>";
        
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
          <textarea class='form-control' name='suggestions' aria-label='Alte specificații'></textarea>
        </div>";
        $output .="<h6> Dorești să schimbi adresa de livrare? <a  href='updateFormAddress/".$id."' >
        Adresa de livrare
        </a></h6>"; 
        $total =0;  
          
        $output.="<table class='table table-bordered border-secundary'>";
       
        $output .=" <thead>";
        $output.="<tr>";
        $output.= "<th> Id</th>";
        $output.= "<th> Nume produs</th>";
        $output.= "<th> Cantitate</th>";
        $output.= "<th> Preț</th>";
        $output.= "<th> Subtotal</th>";
       
        $output .="<th>Șterge </th>";
        $output .="</tr>";
        $output .=" </thead>";
        $output .="<tbody>";
       
          foreach($items as $key=> $item){
              $id =$item['id'];
              $productModel = new ProductsModel;
              $product = $productModel->getProductById($id);
              $productQuantity = $product['quantity'];
               $maxQuantity=0;
              
               if($product['type']=="Pe stoc"){
                 $maxQuantity = $productQuantity- $item['itemQuantity'];
                
                 }
                if($product['type']=="Pe stoc" && $maxQuantity==0){
                  $maxQuantity = $productQuantity;
                }   
              
              $subtotal = $item['itemQuantity']*$item['price'];
              $total += $subtotal;
             $output.="<tr>";
            
             $output .="<td>"."<input type='text' class='form-control-plaintext' name='id' value='".$item['id']."' readonly>"."</input>"."</td>"; 
             
                     
              $output .="<td>"."<input type='text' class='form-control-plaintext' name='name' value='".$item['name']."' readonly>"."</input>"."</td>";
              $output .="<td>"."<input type='number' class='form-control-sm' name='itemQuantity' value='".$item['itemQuantity']."' min='1' max='".$productQuantity."'>"."</input>"."</td>";
              $output .="<td>"."<input type='text' class='form-control-plaintext' name='price' value='".$item['price']."' readonly>"."</input>"."</td>";
              $output .= "<td>"."<input type ='hidden' name='storeId' value='".$item['storeId']."' >"."</input>".$subtotal."</td>";
             
              $output .= "<td><a href='deleteItem/".$id."' class='btn btn-outline-danger text-decoration-none'>
              Șterge</a></td>";
              
              $output.="</tr>";
             
            }
            $output.="</tbody>";
                $output .="</table>";
                $output .="<div class ='row  mb-3'>";
                $output .="<div class ='col  text-end'>";
                $output .="<input type='submit' class='btn btn-dark rounded-1' name='updateOrderItem' formaction='updateOrderItem' formmethod='POST' value='Editează comanda' ></input>";
                $output .="</div>";
                $output .="<div class ='col  text-end'>";
                $output .="<input type='submit' class='btn btn-secondary rounded-1' name='addOrder' value='Salvează comanda' ></input>";
                $output .="</div>";
                $output .="<div class ='col  text-end'>";
                $output .="<h4>"."Total ".$total."</h4>";
                $output .="</div>";
                $output .="</div>";
                $output .="</form>";
          

          return $output;
    }    
      
  }
/*min='".$minQuantity."'*/
    public function table($items){
       // $product = new ProductsModel;
         $output ="";
         
        
         if(is_array($items) ){
           $total =0;  
          
            $output.="<table class='table table-bordered border-secundary'>";
           
            $output .=" <thead>";
            $output.="<tr>";
            $output.= "<th> Id</th>";
            $output.= "<th> Nume produs</th>";
            $output.= "<th> Cantitate</th>";
            $output.= "<th> Preț</th>";
            $output.= "<th> Subtotal</th>";
            $output .="<th> Editează</th>";
            $output .="<th>Șterge </th>";
            $output .="</tr>";
            $output .=" </thead>";
            $output .="<tbody>";
           
              foreach($items as $key=> $item){
                  
                  $id =$item['id'];
                  
                  $subtotal = $item['itemQuantity']*$item['price'];
                  $total += $subtotal;
                 $output.="<tr>";
                 $output .="<form method='POST' action='updateOrderItem/".$id."'>";
                 $output .="<td>"."<input type='text' class='form-control-plaintext' name='id' value='".$item['id']."' readonly>"."</input>"."</td>"; 
                 
                         
                  $output .="<td>"."<input type='text' class='form-control-plaintext' name='name' value='".$item['name']."' readonly>"."</input>"."</td>";
                  $output .="<td>"."<input type='number' class='form-control-sm' name='itemQuantity' value='".$item['itemQuantity']."'>"."</input>"."</td>";
                  $output .="<td>"."<input type='text' class='form-control-plaintext' name='price' value='".$item['price']."' readonly>"."</input>"."</td>";
                  $output .= "<td>"."<input type ='hidden' name='storeId' value='".$item['storeId']."' >"."</input>".$subtotal."</td>";
                  $output.='<td>'.'<input type="hidden" name="id" value="'.$id.'">'.
                  '<input type="submit" name="updateButon" value="Update" class="btn btn-outline-warning rounded-2" onclick="return confirm(\'Sigur vrei să editezi acest produs?\')">'.'</td>';
                  $output .="</form>";
                  $output .= "<td><a href='deleteItem/".$id."' class='btn btn-outline-danger text-decoration-none'>
                  Șterge</a></td>";
                  
                  $output.="</tr>";
                 
                }
                $output.="</tbody>";
                $output .="</table>";
                $output .="<div class ='row  mb-3'>";
                $output .="<div class ='col  text-end'>";
                $output .="<input type='submit' class='btn btn-secondary rounded-1' name='addOrder' value='Salvează comanda' ></input>";
                $output .="</div>";
                $output .="<div class ='col  text-end'>";
                $output .="<h4>"."Total ".$total."</h4>";
                $output .="</div>";
                $output .="</div>";
               
                }
                 
               return $output;
                        
    }
}         

         
     

    


