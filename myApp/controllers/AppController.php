<?php

class AppController
{

    protected $routes = [
                            'home' => 'HomeController',
                            'contact' => 'ContactController',
                            'about' => 'AboutController',
                            'login' => 'LoginController',
                            'signup' => 'SignUpController',
                            'searchUser'=>'SearchUserController',
                            'updateUser'=>'UpdateUserController',
                            'addStore'=>'AddStoreController',
                            'addProduct' => 'AddProductController',
                            'productForm'=>'ProductFormController',
                            'updateProduct'=>'UpdateProductController',
                            'adminProducts'=>'AdminProductsController',
                            'adminOrders' => 'AdminOrdersController',
                            'adminUpdateOrder'=>'AdminUpdateOrderController',
                            'deleteProduct'=>'DeleteProductController',
                            'logout' => 'LogoutController',
                            'adminHome'=>'AdminHomeController',
                            'addShippingAddress' =>'AddShippingAddressController',
                            'updateFormAddress' => 'UpdateFormAddressController',
                            'updateShippingAddress'=>'UpdateShippingAddressController',
                            'filteredProducts' =>'FilteredProductsController',
                            'searchProduct'=>'SearchProductController',
                            'productsByStore'=>'ProductsByStoreController',
                            'addToCart' =>'AddToCartController',
                            'cart' =>'CartController',
                            'deleteItem'=>'DeleteItemController',
                            'updateOrderItem' =>'UpdateOrderItemController',
                            'addOrder'=>'AddOrderController'
                        ];

    public function __construct(){
        
        $this->init();
    }

    public function init(){
        // redirect, page navigation

        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }
        else {
            $page = 'home';
        }

        if(array_key_exists($page, $this->routes)){
            $className = $this->routes[$page];
        }
        else {
            $className = $this->routes['home'];
        }
        new $className;
    }

    public function render($page, $data=array()){
        $template = file_get_contents($page);
            
        // look for all the placeholders
        preg_match_all("[{{\w+}}]", $template, $matches);

        // var_dump($matches[0]);

        foreach($matches[0] as $value){
            // take out all the braces
            // replace them with the information in the date array
            $item = str_replace('{{', '', $value);
            $item = str_replace('}}', '', $item);

            if(array_key_exists($item, $data)){
                $template = str_replace($value, $data[$item], $template);
            }
        }
        return $template;
    }

}