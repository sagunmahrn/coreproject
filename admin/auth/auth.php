<?php
//===========required config file========
require_once ('../config/config.php');

if(!function_exists('auth_check')){
    function auth_check(){
            if(!isset($_SESSION['user_name']) && $_SESSION['is_log_in']!=true){
                $_SESSION['please login first'];
                redirect_to('admin/login');
            }
    }
}