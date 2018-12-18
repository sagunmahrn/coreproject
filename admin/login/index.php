<?php
//===========required config file========
require_once ('../../config/config.php');
//===========required database connection ========
require_once ('../../config/connection.php');

if(!empty($_POST) && $_SERVER['REQUEST_METHOD']=="POST"){
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    $query="SELECT * FROM tbl_users WHERE  username='$username' AND password='$password'";
    $result=mysqli_query($connection,$query);
    $numberOfData=mysqli_num_rows($result);
    if($numberOfData>0){
        $findData=mysqli_fetch_assoc($result);
        $_SESSION['user_id']=$findData['id'];
        $_SESSION['user_name']=$findData['username'];
        $_SESSION['user_email']=$findData['email'];
        $_SESSION['user_image']=$findData['image'];
        $_SESSION['is_login_in']=true;
        redirect_to('admin');
    }
    else{
        $_SESSION['error']='username & password not match';
        redirect_to('admin/login');
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | </title>

    <!-- Bootstrap -->
    <link href="<?=base_url('public/admin/vendors/bootstrap/dist/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?=base_url('public/admin/vendors/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?=base_url('public/admin/vendors/nprogress/nprogress.css')?>" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="<?=base_url('public/admin/vendors/google-code-prettify/bin/prettify.min.css')?>" rel="stylesheet">


    <!-- Custom styling plus plugins -->
    <link href="<?=base_url('public/admin/vendor/select2/dist/css/select2.css')?>" rel="stylesheet">
    <link href="<?=base_url('public/admin/build/css/custom.min.css')?>" rel="stylesheet">

</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <?=messages();?>
                <form action="" method="post">
                    <h1>Login Form</h1>
                    <div>
                        <input type="text" name="username" class="form-control" placeholder="Username"  />
                    </div>
                    <div>
                        <input type="password" name="password" class="form-control" placeholder="Password" />
                    </div>
                    <div class="form-group form-group-sm">
                        <button type="submit" class="btn btn-success btn-sm pull-left" >
                            <i class="fa fa-unlock"></i> Login </button>
                    </div>

                    <div class="clearfix"></div>

                </form>
            </section>
        </div>


    </div>
</div>
<!-- jQuery -->
<script src="<?=base_url('public/admin/vendors/jquery/dist/jquery.min.js')?>"></script>
<!-- Bootstrap -->
<script src="<?=base_url('public/admin/vendors/bootstrap/dist/js/bootstrap.min.js')?>""></script>
<!-- FastClick -->
<script src="<?=base_url('public/admin/vendors/fastclick/lib/fastclick.js')?>""></script>
<!-- NProgress -->
<script src="<?=base_url('public/admin/vendors/nprogress/nprogress.js')?>""></script>
<!-- bootstrap-wysiwyg -->
<script src="<?=base_url('public/admin/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')?> "></script>
<script src="<?=base_url('public/admin/vendors/jquery.hotkeys/jquery.hotkeys.js')?>"></script>
<script src="<?=base_url('public/admin/vendors/google-code-prettify/src/prettify.js')?>"> </script>
<script src="<?=base_url('public/admin/vendors/select2/dist/js/select2.js')?>"> </script>


<!-- Custom Theme Scripts -->
<script src="<?=base_url('public/admin/build/js/custom.min.js')?>"> </script>
<script src="<?=base_url('public/admin/custom/custom.js')?>"> </script>

</body>
</html>
