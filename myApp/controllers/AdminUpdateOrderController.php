<?php
class AdminUpdateOrderController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init(){
        session_start();
        if(isset($_SESSION['store'])){
            $orderModel = new OrdersModel;//updateStatus($status)
            $id = $_GET['id'];
            $pStatus = $_POST['status'];
            echo"<br>";
            echo $pStatus;
            echo"<br>";
            echo $id;
            $order = $orderModel->updateStatus($pStatus);
            //header("refresh: 5; url = ../adminOrders");
            if($order){
                header("Location:../adminOrders");
           
             }else{
                 echo"Ceva nu a mers bine";
                 header("refresh: 5; url = ../adminOrders");
             }
        }
    }
}