<?php

class ProductsModel extends DBModel
{
    protected $name;
    protected $description;
    protected $image;
    protected $price;
    protected $categoryId;
    protected $type;
    protected $quantity;
    protected $storeId;

    public function __construct($pName="P", $pDescription="A", $pImage="I", $pPrice=1.1, $pCategoryId="J", $pType="O", $pQuantity=0, $pStoreId=1){
        $this->name=$pName;
        $this->description=$pDescription;
        $this->image=$pImage;
        $this->price=$pPrice;
        $this->categoryId=$pCategoryId;
        $this->type=$pType;
        $this->quantity=$pQuantity;
        $this->storeId=$pStoreId;
    }


    //Add product
    public function addProduct($pName,$pDescription, $pImage,$pPrice, $pCategoryId, $pType, $pQuantity, $pStoreId ){
        
        $q = "INSERT INTO `products`(`name`,`description`, `image`,`price`, `categoryId`, `type`,`quantity`, `storeId`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
        // prepared statements
        $myPrep = $this->db()->prepare($q);
        // s - string, i - integer, d - double, b - blob
        
        $myPrep->bind_param("sssdisii",$pName, $pDescription, $pImage, $pPrice, $pCategoryId, $pType, $pQuantity, $pStoreId);
       
        return $myPrep->execute();
        
        // $myPrep->close();
    }

    

     public function getAllProductsByStoreId($idStore){
         $sql = "SELECT *  FROM `products` WHERE `storeId`= $idStore;"; 
          $result = $this->db()->query($sql)->fetch_all(MYSQLI_ASSOC);
          return $result;
     }

   //Find product by id
   public function findProductById($pId){
    $sql = "SELECT * FROM `products` WHERE `id` = ? ; ";
    $myPrep = $this->db()->prepare($sql);
    $myPrep->bind_param("i", $pId);
    $myPrep->execute();
    $result = $myPrep->get_result();
    return $result->fetch_assoc();
   }

   // 
   public function filterProductsByCategoryId($id){
    //$id = $_GET['id'];
    $sql = "SELECT * FROM `products` WHERE `categoryId` = ? ; ";
    $myPrep = $this->db()->prepare($sql);
    $myPrep->bind_param("i", $id);
    $myPrep->execute();
    $result = $myPrep->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
   }
    
   //UPDATE PRODUCT
   public function updateProductWithImage($pName,$pDescription, $pImage,$pPrice, $pCategoryId, $pType, $pQuantity){
     $id = $_GET['id'];
    $sql="UPDATE `products` SET `name` = ?, `description` = ?,`image` = ?,`price` = ?,`categoryId` = ?,`type` = ?,`quantity` = ? WHERE `products`.`id` = $id";
    $myPrep = $this->db()->prepare($sql);
    $myPrep->bind_param("sssdisi",$pName, $pDescription, $pImage, $pPrice, $pCategoryId, $pType, $pQuantity);
       
    return $myPrep->execute();
   }

    //UPDATE PRODUCT
    function updateProduct($pName,$pDescription,$pPrice, $pCategoryId, $pType, $pQuantity){
      $id = $_GET['id'];
     $sql="UPDATE `products` SET `name` = ?, `description` = ?,`price` = ?,`categoryId` = ?,`type` = ?,`quantity` = ? WHERE `products`.`id` = $id";
     $myPrep = $this->db()->prepare($sql);
     $myPrep->bind_param("ssdisi",$pName, $pDescription,  $pPrice, $pCategoryId, $pType, $pQuantity);
        
     return $myPrep->execute();
    }

   //Function delete product
   function deleteProduct($id){
     $q = "SELECT * FROM `products` WHERE `id` = $id";
     $res = $this->db()->query($q);
     $row = $res->fetch_assoc();
     unlink("myApp/img/".$row['image']);
       $sql= "DELETE  FROM `products` WHERE `id` = $id";
       $result = $this->db()->query($sql);

    if($result) return true;
    else return false;
   }

   //Function to get all products
   function getAllProducts(){
    $sql = "SELECT * FROM products;";
    $result = $this->db()->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
   }

   public function getByMultipleIds( ){
    $ids = [10,15,16,17];
    $idsStr = implode("," ,$ids);
    $sql = "SELECT * from products WHERE id IN( $idsStr )";
    $result = $this->db()->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
    // // number of question marks
    // $questionMarksCount = count($ids);
    // // create a array with question marks
    // $questionMarks = array_fill(0, $questionMarksCount, '?');
    // // join them with ,
    // $questionMarks = implode(',', $questionMarks);
    // // data types for bind param
    // $dataTypes = str_repeat('i', $questionMarksCount);
    
    // $stmt = $this->db() -> prepare("SELECT `name`, `storeId` FROM `products` WHERE id IN ($questionMarks)");
    
    // $stmt -> bind_param($dataTypes, ...$ids);
    // $stmt -> execute();
    // $stmt -> store_result();
    // $stmt -> bind_result($name, $storeId);
    // $result = $stmt->get_result();
    // return $result->fetch_all(MYSQLI_ASSOC);
   }

   public function filterProductsByName($searchTerm){
    $sql = "SELECT * FROM `products` WHERE LOWER(name) LIKE '$searchTerm%';";
    $result = $this->db()->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
   }
  
     
}
