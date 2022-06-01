<?php
class AdminOrdersController extends AppController
{
    public function __construct(){
        $this->init();
    }
    public function init(){
        session_start();
        if(isset($_SESSION['store'])){
            $userStore=$_SESSION['store'];
            $ordersModel = new OrdersModel;
            $storeModel = new StoresModel;
            $storeByName = $storeModel->getStoreByName($userStore);
            $storeId = $storeByName['id'];
            
            $orders =$ordersModel->getAllOrdersByStoreId($storeId);
            if(!empty($orders)){
                $data['title'] = 'Admin Products Page';
                $data['message'] = "<h2 class='fst-italic text-success text-uppercase'>Comenzi din:  $userStore  </h2>";
           
                $data['tableContent'] = $this->showOrders($orders);
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'adminProductsView.html', $data);
               echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
            }else{
                $data['title'] = 'Admin Products Page';
            $data['message'] = "<h2 class='fst-italic text-success text-uppercase'> $userStore  </h2>";
       
            $data['tableContent'] ="<h2 class='fst-italic text-danger '>Nu ai comenzi  </h2>" ;
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'adminProductsView.html', $data);
            }

        }else{
            $data['title'] = 'Admin Home PAGE';
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'mainAdminHomeView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'adminView.html',$data);
        }
    }

    public function showOrders($orders){
        $output="";
        if(is_array($orders)){
            $output .= "<table class='table table-striped mx-auto w-auto small' style='width:100%' ><tr>";
            $output.= "<th>Id</th>";
            $output.= "<th> Data</th>";
            $output.= "<th> Status</th>";
            $output.= "<th>Sugestii</th>";
            $output.= "<th>Client</th>";
            $output.= "<th>Email</th>";
            $output.= "<th>Adresa</th>";
            $output.= "<th>Telefon</th>";
            $output.= "<th>Produs</th>";
            $output.= "<th> Cantitate</th>";
            $output .="<th> Preț</th>";
            $output .="<th>Subtotal</th>";
            $output .="<th>Editează</th>";
            $output .="<th>Șterge</th>";
            $output .="</tr>";
            $subtotal = 0;
            $total = 0;
            foreach($orders as  $row){
                $id=intval($row['id']);
                $findedSuggestions = $row['suggestions'];
                $strSugestions ="";
                $total += $subtotal;
                if(is_null($row['suggestions'])){
                    $strSugestions ="Nu am";
                }else{
                    $strSugestions=$row['suggestions'];
                }
                $subtotal = $row['itemQuantity']*$row['price'];
                $output .="<tr>";
                $output .="<form method='POST' action='adminUpdateOrder/".$id."'>";
                $output .="<td>".$id."</td>";
                $output .="<td>".$row['date']."</td>";
                $output .="<td>"."<select class='form-select' aria-label='Default select example'  name='status' required>".
                         "<option value='".$row['status']."'>".$row['status']."</option>".
                          "<option value='AFFECTED'>"."AFFECTED"."</option>".
                          "<option value='ACCEPTED'>"."ACCEPTED"."</option>".
                          "<option value='REFUSED'>"."REFUSED"."</option>".
                          "<option value='DELIVERED'>"."DELIVERED"."</option>".
                          "</select>"."</td>";
                $output .="<td>".$strSugestions."</td>";
                $output .="<td>".$row['fullName']."</td>";
                $output .="<td>".$row['email']."</td>";
                $output .="<td>".$row['county']." ".$row['city']." ".$row['address']." ".$row['zipCode']."</td>";
                $output .="<td>".$row['phone']."</td>";
                $output .="<td>".$row['name']."</td>";
                $output .="<td>".$row['itemQuantity']."</td>";
                $output .="<td>".$row['price']."</td>";
                $output .="<td>".$subtotal."</td>";
                $output .="<td>". '<input type="submit" name="updateButon" value="Update" class="btn btn-warning rounded-pill" onclick="return confirm(\'Sigur vrei să editezi această comandă?\')">'.'</td>';

                $output .="</form>";
                $output .="<td>".
                        "<button name='deleteOrder'  class='btn btn-danger rounded-pill' onclick='return confirm(\"Sigur vrei să ștergi această comandă?\")'>".
                        "<a class='text-light text-decoration-none '  href='adminDeleteOrder/".$id."' >".
                        "Delete"."</a>"."</button>"."</td>";
                $output .="</tr>";
            }
           
                $output .="</table>";
                $output .="<div class='float-end'>";
                $output .="<p>Total comenzi <strong><span> $total </span></strong><span>Lei</span></p>";
                $output .="</div>";
        }
        return $output;
    }
}