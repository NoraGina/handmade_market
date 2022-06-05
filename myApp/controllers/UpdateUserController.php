<?php
class UpdateUserController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
       
        //FORM DATA
        $id = $_POST['id'];
        $uFullname = $_POST['updateFullname'];
        $uUsername = $_POST['updateUsername'];
        $uEmail = $_POST['updateEmail'];
        $uPass = $_POST['updatePassword1'];
        $uRole = $_POST['role'];
        $hash = password_hash($uPass, PASSWORD_DEFAULT);
        //INSTANTIATING A NEW USER
        $userModel = new UsersModel;
        $updatedUser = $userModel->updateUser($uFullname, $uUsername, $uEmail, $uPass, $hash, $uRole);
      if($updatedUser){
          session_start();
          $_SESSION['user']=$uUsername;
          $role = $_POST['role'];
          $_SESSION['cart']=array();
          header('Location:home');
      }
    }
}