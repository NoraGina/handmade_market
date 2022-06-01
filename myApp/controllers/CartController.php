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
          echo'<pre>';
          var_dump($items);
          echo'</pre>';
          echo'<br>';
          echo'Keys';
          print_r(array_keys($items));
          echo'<br>';
          echo"Key";
          print_r(key($items));
            $loggedInUser = $_SESSION['user'];
             $user = new UsersModel;
             $customer = $user->getOne($loggedInUser);
             $userId = $customer['id'];
             $address = new ShippingAddressModel;
             $addressCart = $address->getShippingAddressByUserId($userId);
          
               $firstRow = $items[0];
               $firstKey = $firstRow['id'];
               $storeCart =$firstRow['storeId'];
              
              
              $data['title']="Cart Page";
              
              $data['navList'] = $this->bindLinkItems();
              $data['inputs'] = $this->outputCart( $customer, $addressCart );
              $data['table']=$this->table($items);
              $data['mainContent'] = $this->render(APP_PATH.VIEWS.'cartView.html', $data);
              echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
          
           
        }else{
          echo "<h2 class='fst-italic text-success text-uppercase'> Nu ai produse în coș </h2>";
          header("Refresh:2; url=home");
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

    public function outputCart( $user, $address){
        $output ="";
        if(is_array($user) && is_array($address) ){

           
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
            <textarea class='form-control' name='suggestions' aria-label='Alte specificații'></textarea>
          </div>";
            

            return $output;
      }    
        
    }

     public function table($items){
        $product = new ProductsModel;
         $output ="";
         
         $name ="";
         if(is_array($items) ){
             $output.="<table class='table table-bordered border-secundary'>";
             $output.="<tr>";
             $output.= "<th> Id</th>";
             $output.= "<th> Nume produs</th>";
              $output.= "<th> Cantitate</th>";
             $output.= "<th> Preț</th>";
              $output.= "<th> Subtotal</th>";
              $output .="<th> Șterge</th>";
              $output .="<th> Editează</th>";
           
              $output .="</tr>";
              $total =0;
              foreach($items as $key=> $item){
                //$key = key($item);
                  $subtotal = $item['quantity']*$item['price'];
                 $total += $subtotal;
                
                 $intKey = intval($key);
                 $output .="<tr>";
                 $output .="<td>"."<input type='text' class='form-control-plaintext' name='productId' value='".$item['id']."' readonly>"."</input>"
                 ."</td>";
                 $output .="<td>"."<input type='text' class='form-control-plaintext' name='name' value='".$item['name']."' readonly>"."</input>"."</td>";
                 $output .="<td>"."<input type='text' class='form-control-sm' name='itemQuantity' value='".$item['quantity']."'>"."</input>"."</td>";
                 $output .="<td>"."<input type='text' class='form-control-plaintext' name='price' value='".$item['price']."' readonly>"."</input>"."</td>";
                 $output .= "<td>".$subtotal."</td>";

                 $output .= "<td><a href='deleteItem/".$intKey."' class='btn text-decoration-none' name>
                 <input type='hidden' value='".$intKey."' name='key'></input>
                 Șterge</a><td>";
                 
                 
                $output .= "<td><form action='updateOrderItem/".$intKey."'' method='POST'>
                            <input type='hidden' value='".$item['id']."'></input>
                            <input type='sumbit' class='btn' value='Șterge'/> </form></td>";

                 $output.="</tr>";
                 
                
              }

                       
                 }
                       
             $output .="</table>";
             $output .="<h4 class='ms-auto'>"."Total ".$total."</h4>";
             $output .="<input type='submit' class='btn btn-secondary' value='Salvează comanda'></input>";
             return $output;
                        
                }
             

         
     

    
}

