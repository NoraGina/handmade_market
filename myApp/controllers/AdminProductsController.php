<?php
class AdminProductsController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        echo __FILE__;
        session_start();
        if(isset($_SESSION['store'])){
            $userStore=$_SESSION['store'];
            
            $product = new ProductsModel;
            $store = new StoresModel;
            $storeByName = $store->getStoreByName($userStore);
            $storeId = $storeByName['id'];
            
            $products =$product->getAllProductsByStoreId($storeId);
          if(!empty($products)){
            $data['title'] = 'Admin Products Page';
            $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>Lista cu produse din:  $userStore  </h2>";
       
            $data['tableContent'] = $this->getAllProductsAdmin($products);
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'adminProductsView.html', $data);
           echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
          }else{
            $data['title'] = 'Admin Products Page';
            $data['message'] = "<h2 class='fst-italic text-success text-uppercase'> $userStore  </h2>";
       
            $data['tableContent'] ="<h2 class='fst-italic text-danger '>Nu ai produse în magazin  </h2>" ;
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'adminProductsView.html', $data);
           echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
          }
           
                
            
           
        }else{
            $data['title'] = 'Admin Home PAGE';
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
        }
    }

          //Get all products by store id into table for admin
  public function getAllProductsAdmin($result){
    $category = new CategoriesModel;
    $catOutput = $this->bindSelect();
    $output = "";
    if(is_array($result)){
        $output .= "<table class='table table-striped mx-auto w-auto' style='width:100%' ><tr>";
        
            $output.= "<th> Nume produs</th>";
            $output.= "<th> Descriere</th>";
            $output.= "<th> Imagine</th>";
            $output.= "<th> Preț</th>";
            $output.= "<th> Categorie</th>";
            $output.= "<th> Tip</th>";
            $output.= "<th> Cantitate</th>";
            $output .="<th> Editează</th>";
            $output .="<th> Șterge</th>";
            $output .="</tr>";
        //iterate over array
        foreach($result as  $row){
            $id=$row['id'];
            $output .="<tr>";
            $output .="<form method='POST' action='updateProduct/".$id."'>";
            $output .="<td>"."<input type='text' class='form-control-sm' name='name' value='".$row['name']."'>"."</input>"."</td>";
            $output .="<td>"."<textarea class='form-control' name='description' id='exampleFormControlTextarea1'
            style='height: 100px' required>".$row['description']."</textarea>"."</td>";
            $output .="<td>"."<input type='file'  name='myImage' class='form-control' id='form6Example6'>".
                    "<img src='myApp/img/".$row['image']."' height='80px' width='80px'/> ".
                     "</td>";
            $output .="<td>"."<input type='text' style='width:60px' class='form-control-sm' name='price' value='".$row['price']."'>"."</input>"."</td>";
            $output .="<td>". $catOutput."</td>";
            
            $output .="<td>"."<select class='form-select' aria-label='Default select example' value='".$row['type']."' name='type' required>".
            "<option value='".$row['type']."'>".$row['type']."</option>".
            "<option value='La comandă'>"."La comandă"."</option>".
            "<option value='Pe stoc'>"."Pe stoc"."</option>"."</select>"."</td>";
            $output .="<td>"."<input type='number' style='width:60px' class='form-control-sm' name='quantity' value='".$row['quantity']."'>"."</input>"."</td>";
            $output.='<td>'.'<input type="hidden" name="id" value="'.$id.'">'.
            '<input type="submit" name="updateButon" value="Update" class="btn btn-warning rounded-pill" onclick="return confirm(\'Sigur vrei să editezi acest produs?\')">'.'</td>';
            
            $output .="</form>";
            $output .="<td>".
            "<button name='deleteProduct'  class='btn btn-danger rounded-pill' onclick='return confirm(\"Sigur vrei să ștergi acest produs?\")'>".
            "<a class='text-light text-decoration-none '  href='deleteProduct/".$id."' >".
            "Delete"."</a>"."</button>"."</td>";
            
    }
    $output .="</table>";
    
        return $output;
    }

    
    }

    public function bindSelect(){
        $category = new CategoriesModel;
        $categories = $category->getAllCategories();
        
        $output = '';
        if(is_array($categories)){
             $output .= "<select class='form-select' style='width:160px' name='categoryId' >";
               
                foreach($categories as $row){
                    
                    $output .= '<option value="'.$row["id"].'">'.$row["name"].'</option>';
                }
                $output .= '</select>';
           
           
        }
        
       
        return $output;
    }

    
   
    
}