<?php
class ProductFormController extends AppController
{
    public function __construct(){
        $this->init();
    }
    public function init(){
        session_start();
        if(isset($_SESSION['store'])){
            $userStore=$_SESSION['store'];
            
            $data['title'] = 'Add product PAGE';
            $category = new CategoriesModel;
            $categories = $category->getAllCategories();
            $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>Hello  $userStore  </h2>";
            $data['select'] = $this->bindSelect($categories);
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addProductView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'adminView.html', $data);
    }else{
        $data['title'] = 'Admin home page';
        $data['mainContent'] = "<h2 class='fst-italic text-danger'>Something went wrong  </h2>";
        $data['mainContent'].= $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
    }
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