<?php
//===========required config file========
require_once ('../config/config.php');
//===========required database connection ========
require_once ('../config/connection.php');
//========required authentication========
require_once('auth/auth.php');

$url = htmlspecialchars(isset($_GET['url']) ? $_GET['url'] : 'dashboard');
$title = ucfirst($url);
$url = $url.'.php';

auth_check();

?>
<?php
//============required header file==========
require_once root_path("admin/backend/layouts/header.php");
?>

<?php
$pagePath=root_path('admin/backend/pages/'.$url);

if(file_exists($pagePath)&& is_file($pagePath)){
    require_once root_path("admin/backend/layouts/aside.php");
    require_once root_path("admin/backend/layouts/top_nav.php");
    require_once $pagePath;
}
else{
    require_once root_path('admin/help/404.php');
}
?>
<?php
//============require footer file=======
require_once root_path('admin/backend/layouts/footer.php');
?>