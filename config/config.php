<?php
session_start();
ob_start();
error_reporting(E_ALL);
error_reporting('-1');
date_default_timezone_set('Asia/Kathmandu');

$serverName=$_SERVER['SERVER_NAME'];
if($serverName=='localhost'){
    $serverName="http://localhost/coreproject/";
}
else{
    $serverName="http://localhost/coreproject/";
}
//============base url=============
if(!function_exists('base_url')){
    function base_url($path = null)
    {
        $server=$GLOBALS['serverName'];
        return $server.$path;
    }
}

if(!function_exists('admin_url')){
    function admin_url($path=null){
       return base_url('admin/'.$path);
    }
}


//=============root path====
if(!function_exists('root_path')){
    function root_path($path = null){
        $path=trim($path,'/');
        return dirname(dirname(__FILE__)).'/'.$path;
    }
}


//==========pre============
if (!function_exists('pre')) {
    function pr($data = array())
    {
        echo "<pr>";
        print_r($data);
        echo "</pr>";
    }
}

if (!function_exists('pre')) {
    function dd($data = array())
    {
        echo "<pr>";
        var_dump($data);
        echo "</pr>";
    }
}

if (!function_exists('messages')) {
    function messages()
    {
        if (isset($_SESSION['success'])) {
            $class = 'alert alert-success';
            $message = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            $class = 'alert alert-danger';
            $message = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        $output = '';
        if (isset($message)) {
            $output .= "<div class='{$class}'>";
            $output .= $message;
            $output .= "</div>";
        }
        return $output;
    }
}
if (!function_exists('redirect_to')) {
    function redirect_to($path)
    {
        $path = explode('/', $path);
        $redirectPath = base_url($path[0] . '/' . $path[1]);
        header('Location:' . $redirectPath);
        exit();

    }
}

if (!function_exists('date_time')) {
    function date_time()
    {
        return date('Y-m-d h:i:s');
    }
}
