<?php
class AddProductController extends AppController
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
            //FORM DATA
            $pName = $_POST['name'];
            $pDescription = $_POST['description'];
            $pPrice = $_POST['price'];
            $pCategory = $_POST['categoryId'];
            $pCategoryId = intval( $pCategory);
            $pType = $_POST['type'];
            $pQuantity = $_POST['quantity'];
            $pStoreId = $storeId;
            //IMAGE
            $imgName = $_FILES['myImage']['name'];
            $imgSize = $_FILES['myImage']['size'];
	        $tmpName = $_FILES['myImage']['tmp_name'];
	        $error = $_FILES['myImage']['error'];
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
                        //$newImgFilename = uniqid("IMG-", true).'.'.$imgExLc;
                        $imgUploadPath = 'myApp/img/'.$newImgFilename;
                        move_uploaded_file($tmpName, $imgUploadPath);

                        // Insert into Database
                        $product = new ProductsModel;
                        
                        if($product->addProduct($pName,$pDescription, $newImgFilename,$pPrice,  $pCategoryId, $pType, $pQuantity, $pStoreId )){
                            
                                header('Location: adminProducts');
                                
                            
                        }else{
                            $data['title'] = 'Admin PAGE';
                            $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>  Ceva nu a mers bine!</h2>";
                            $data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                            echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                        }
                   }else{
                        $em = "You can't upload files of this type";
                        $data['title'] = 'Admin PAGE';
                        $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>  $em  </h2>";
                        $data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
                        echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
                   }
                }
            }//End if Error ===0
            else{
                $em = "unknown error occurred!";
                $data['title'] = 'Admin PAGE';
                $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'>  $em  </h2>";
            
                $data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
               echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
            }
        }//End if isset($_SESSION)
        else{
            if(isset($_SESSION['user'])){
                $loggedInUserAdmin=$_SESSION['user'];
                $user = new UsersModel;
                $data['title'] = 'Admin PAGE';
                $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>  $loggedInUserAdmin  nu ai încă un magazin </h2>";
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addStoreView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
            }else{
                $data['title'] = ' Home PAGE';
                $data['mainContent'] = '<h2 class="text-uppercase text-danger">Trebuie să te loghezi!</h2>';
                echo $this->render(APP_PATH.VIEWS.'layout.html', $data);
            }
            $data['title'] = ' Home PAGE';
            $data['mainContent'] = '<h2 class="text-uppercase text-danger">Ceva nu a mers bine!</h2>';
            echo $this->render(APP_PATH.VIEWS.'layout.html', $data);
            
        }
    }

    public function stringMaker($str){
        if ($str == trim($str) && preg_match('/\s/',$str)) {
            return str_replace(' ', '_', $str);
        }
        return $str;
    }

    public function bindSelect($categories){
        $output ="";
        $output .= "<select class='form-select' name='categoryId' >";
        $output .='<option value = "">Alege o categorie</option>';
        foreach($categories as $category){
            $output .= '<option value="'.$category["id"].'">'.$category["name"].'</option>';
        }
        $output .= '</select>';
        return $output;
    }
}