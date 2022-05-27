<?php
class AboutController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        echo __FILE__;
        session_start();
        if(isset($_SESSION['user'])){
        $data['title'] = 'About Private PAGE';
        $category = new CategoriesModel;
        $data['navList'] = $this->bindLinkItems();
        $data['mainContent']=$this->render(APP_PATH.VIEWS.'aboutView.html', $data);
        echo $this->render(APP_PATH.VIEWS.'customerView.html',$data);
        }else{
            $data['title'] = 'About  PAGE';
            $category = new CategoriesModel;
            $data['navList'] = $this->bindLinkItems();
            $data['mainContent']=$this->render(APP_PATH.VIEWS.'aboutView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'publiclayoutView.html',$data);
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
         "<a class='nav-link active' aria-current='page' href='/HandMadeMarket/filteredProducts/".$id."'>".$name."</a>".
          " </li>";
            
           
        }
        return $output;
       }
    }

    
}