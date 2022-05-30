<?php
class UsersModel extends DBModel
{
    protected $fullName;
    protected $userName;
    protected $email;
    protected $password;
    protected $hashedPassword;
    protected $role;

    public function __construct($uFullName='J', $uUserName='D', $uEmail='email', $uPass='pass', $uHash='dfddfsff', $uRole='cust'){
        $this->fullName = $uFullName;
        $this->userName = $uUserName;
        $this->email = $uEmail;
        $this->password = $uPass;
        $this->hashedPassword = $uHash;
        $this->role =$uRole;
    }

    
    //add user
    public function addUser($name,$user, $email,$pass,$hPass, $role ){
        
        $q = "INSERT INTO `users`(`fullName`,`userName`, `email`,`password`, `hashedPassword`, `role`) VALUES (?, ?, ?, ?, ?, ?);";
        // prepared statements
        $myPrep = $this->db()->prepare($q);
        // s - string, i - integer, d - double, b - blob
        
        $myPrep->bind_param("ssssss",$name, $user, $email, $pass, $hPass, $role);
       
        return $myPrep->execute();
        
        // $myPrep->close();
    }
//check username and password for login
    public function isAuth($uName, $uPass){
        $sql = "SELECT * FROM `users` WHERE `userName`= ? ";
        $myPrep = $this->db()->prepare($sql);
        $myPrep->bind_param("s", $uName);
        $myPrep->execute();
        $result = $myPrep->get_result()->fetch_assoc();
        //echo $result['password'];
        if(password_verify($uPass, $result['hashedPassword'])){
                return $result;
            }
            else{
                return false;
            } 
       
        
    }

     //function get one user user   
     public function getOne($uName){
        $sql = "SELECT * FROM `users` WHERE `userName` = ? ; ";
        $myPrep = $this->db()->prepare($sql);
        $myPrep->bind_param("s", $uName);
        $myPrep->execute();
        $result = $myPrep->get_result();
        return $result->fetch_assoc();
    }

    

    public function getUserById($userId){
        $sql = "SELECT * FROM `users` WHERE `id` = ? ; ";
        $myPrep = $this->db()->prepare($sql);
        $myPrep->bind_param("i", $userId);
        $myPrep->execute();
        $result = $myPrep->get_result();
        return $result->fetch_assoc();
    }

    public function getAllCustomers(){
        $role = "CUSTOMER";
        $sql ="SELECT * FROM `users` WHERE `role`='CUSTOMER';";
        $result = $this->db()->query($sql);
		return $result->fetch_all(MYSQLI_ASSOC);
    }

}