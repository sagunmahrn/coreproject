<?php
//===========required config file========
require_once ('config/config.php');
require_once ('config/connection.php');

$url=htmlspecialchars( isset($_GET['url'])?$_GET['url']:'home');
$title=ucfirst($url);
$url=$url.'.php';

?>
<?php
//============required header file==========
require_once root_path('frontend/layouts/header.php');
?>

<?php
$pagePath=root_path('frontend/pages/'.$url);

if(file_exists($pagePath)&& is_file($pagePath)){
    require_once root_path('frontend/layouts/top_header.php');
    require_once root_path('frontend/layouts/nav.php');
    require_once $pagePath;
    require_once root_path('frontend/layouts/main_footer.php');
}
else{
    require_once root_path('help/404.php');
}
?>
<?php
//============require footer file=======
require_once root_path('frontend/layouts/footer.php');
?>