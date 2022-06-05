<?php
class UpdateProductController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
      
        session_start();
        if(isset($_SESSION['store'])){
            $userStore=$_SESSION['store'];
            //var_dump($userStore);
            $store = new StoresModel;
            $storeByName = $store->getStoreByName($userStore);
            $storeId = $storeByName['id'];
            $product = new ProductsModel;
            
             //FORM DATA
            $pName = $_POST['name'];
            $pDescription = $_POST['description'];
            $pPrice = $_POST['price'];
            $pCategory = $_POST['categoryId'];
            $pCategoryId = intval( $pCategory);
            $pType = $_POST['type'];
            $pQuantity = $_POST['quantity'];
            $oldimage = $_POST['previous'];
            echo"<br>";

           // $newImage = $_FILES['image']['name'];
            $pStoreId = $storeId;

            //IMAGE isset($_FILES['image']['name'])
            $imgName = $_FILES['image']['name'];
            $imgSize = $_FILES['image']['size'];
            $tmpName = $_FILES['image']['tmp_name'];
            $error = $_FILES['image']['error'];
           $id = $_GET['id'];
           echo $id;
            if($imgName != ''){
               
                if($error === 0){
                    if($imgSize > 1000000){
                        $em = "Sorry, your file is too large.";
                        $data['title'] = 'Admin PAGE';
                        $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>  $em</h2>";
                        $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                        echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                    }else{
                        $imgEx = pathinfo($imgName, PATHINFO_EXTENSION);
                        //echo $img_ex;
                        $imgExLc = strtolower($imgEx);
                        $allowedExs = array("jpg", "jpeg", "png"); 
                    if(in_array($imgExLc, $allowedExs)){
                            $imgNameNew = $this->stringMaker($pName);
                            $newImgFilename =uniqid($imgNameNew."-", true).'.'.$imgExLc;
                            
                            $imgUploadPath = 'myApp/img/'.$newImgFilename;
                            move_uploaded_file($tmpName, $imgUploadPath);
                            
                           
                               $updateProd= $product->updateProductWithImage($pName,$pDescription, $newImgFilename,$pPrice,  $pCategoryId, $pType, $pQuantity);
                               if($updateProd){
                                unlink("myApp/img/".$oldimage);
                                //header("Refresh:6; url=../adminProducts");
                                header('Location:../adminProducts');
                                
                                }else{
                                    $data['title'] = 'Admin PAGE';
                                    $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>  Ceva nu a mers bine!</h2>";
                                    $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                                    echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                                }
                            
                            
                    }else{
                        $em = "You can't upload files of this type";
                        $data['title'] = 'Admin PAGE';
                        $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>  $em  </h2>";
                        $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                        echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                    }
                    }
                }else{
                    $em = "unknown error occurred!";
                    $data['title'] = 'Admin PAGE';
                    $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>  $em  </h2>";
                
                    $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                }
            }else{
                if($product->updateProduct($pName,$pDescription, $pPrice,  $pCategoryId, $pType, $pQuantity)){
                    header('Location:../adminProducts');
                    // header("Refresh: 6; url = ../adminProducts") ;
                    
                }else{
                    $data['title'] = 'Admin PAGE';
                    $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>  Ceva nu a mers bine!</h2>";
                    $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                    echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                }
            }
        }else{
            header('Location:home');
            
        }
    }

    public function stringMaker($str){
        if ($str == trim($str) && preg_match('/\s/',$str)) {
            return str_replace(' ', '_', $str);
        }
        return $str;
    }

             //Get all products by store id into table for admin
  public function getAllProductsAdmin($result){
    
    $output = "";
    if(is_array($result)){
        $output .= "<table class='table table-striped mx-auto w-auto' ><tr>";
        foreach(array_keys($result[0]) as $key){
            $output .="<th>".$key." </th>";
            
        }
        //add a new column
            $output .="<th> Editează</th>";
            $output .="<th> Șterge</th>";
            $output .="</tr>";
        //iterate over array
        foreach($result as $row){
            $id=$row['id'];
            $output .="<tr>";
            $output .="<td>".$id. "</td>";
            $output .="<td>".$row['name']."</td>";
            $output .="<td>".$row['description']."</td>";
            $output .="<td>"."<img src='myApp/img/".$row['image']."' height='80px' width='80px'/> "."</td>";
            $output .="<td>".$row['price']." Lei"."</td>";
            $output .="<td>".$row['categoryId']."</td>";
            $output .="<td>".$row['type']."</td>";
            $output .="<td>".$row['quantity']."</td>";
            $output .="<td>".$row['storeId']."</td>";
            $output .="<td>".
            "<button  class='btn btn-warning rounded-pill' onclick='return confirm(\"Are you sure you want to update product?\")'>".
            "<a class='text-light text-decoration-none '  href='updateProduct/".$id."' >".
            "Edit"."</a>"."</button>"."</td>";
            $output .="<td>".
            "<button name='deleteProduct'  class='btn btn-danger rounded-pill' onclick='return confirm(\"Sigur vrei să ștergi acest produs?\")'>".
            "<a class='text-light text-decoration-none '  href='deleteProduct/".$id."' >".
            "Delete"."</a>"."</button>"."</td>";
            
    }
    $output .="</table>";
    
        return $output;
    }

    
    }
}