<?php
class CategoriesModel extends DBModel
{
    protected $name;

    public function __construct($name="J"){
        $this->name=$name;
    }

   

    public function getAllCategories(){
        $sql = "SELECT * FROM categories;";
        $result = $this->db()->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    

    function getIdByName($cName){
        $sql = "SELECT `id` FROM `categories` WHERE `name` = $cName;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }

    function findCategoryById($cId){
        $sql = "SELECT `name` FROM `categories` WHERE `id` = $cId;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }
}
    
    
