<?php
class SearchUserController extends AppController
{
    public function __construct(){
        $this->init();
    }
    public function init(){
        
        $email= $_POST['searchEmail'];
         
         $userModel = new UsersModel;
         $editedUser = $userModel->findUserByEmail($email);
        $categoryModel = new CategoriesModel;
        $categories = $categoryModel->getAllCategories();
          if($editedUser){
          
            $data['fullName'] =  $editedUser['fullName'];
            $data['userName'] =  $editedUser['userName'];
            $data['email'] =  $editedUser['email'];
            $data['role'] =  $editedUser['role'];
            $data['id'] =  $editedUser['id'];
            $data['title'] = 'Update user';
            $data['navList'] = $this->bindLinkItems($categories); 
            $data['mainContent']= $this->render(APP_PATH.VIEWS.'updateUserFormView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html', $data);
          }else{
              echo 'Ceva nu a mers bine!';
          }
       
    }
    public function bindLinkItems($categories){
       
        $output = "";
       if(is_array($categories)){
        foreach($categories as $category){
            $id = $category['id'];
            $name = $category['name'];
            //$stringLink =$this->urlString($name);
            
                $output .="<li class='nav-item'>".
         "<a class='nav-link active' aria-current='page'  href='/TestHandMade/filtredProducts/".$id."'>".$name."</a>".
          " </li>";
            
           
        }
        return $output;
       }
    }
}