<?php
class DeleteProductController extends AppController
{
    public function __construct(){
        $this->init();
    }
    public function init(){
        //echo __FILE__;
        session_start();
        if(isset($_SESSION['store'])){
           
            $userStore=$_SESSION['store'];
            
            $store = new StoresModel;
            $storeByName = $store->getStoreByName($userStore);
          
            $storeId = $storeByName['id'];
             
            $product = new ProductsModel;
             $id=$_GET['id'];
             //calling the method delete
             if($product->deleteProduct($id)){
               //header("refresh: 3; url = ../adminProducts") ;
                 header('Location: ../adminProducts');
               
             }else{
                $data['title'] = 'Admin  PAGE';
                $data['message'] = "<h2 class='fst-italic text-danger text-uppercase'> Ceva nu a mers bine</h2>";
                $data['tableContent'] = $product->getAllProductsAdmin($storeId);
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'adminProductsView.html', $data);
            
                echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
             }
            
            
        }else{
            $data['title'] = 'Admin Home PAGE';
            $data['mainContent'] .= $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
        }
       
    }
}