<?php
$select = "SELECT * FROM tbl_privileges WHERE status = 1 ORDER BY id DESC";
$privilegeResult = mysqli_query($connection, $select);

if (!empty($_POST) && !empty($_FILES) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    if (empty($name)) {
        $_SESSION['error'] = 'name field is required';
        redirect_to('admin/add_user');
    }
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['password_confirmation']);
    if ($password != $cpassword) {
        $_SESSION['error'] = 'Password not match';
        redirect_to('admin/add_user');
    }
    $cDate = date_time();
    $uDate = date_time();
    //=================upload image=========
    $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
    $imageName = md5(microtime()) . '.' . $ext;
    $tmpName = $_FILES['upload']['tmp_name'];
    $error = $_FILES['upload']['error'];
    $fileUploadErrors = array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );
    $uploadPath = root_path('public/images/users');
    if (!function_exists($uploadPath)) {
        mkdir($uploadPath);
  }
    if ($error == 0) {
        if (!move_uploaded_file($tmpName, $uploadPath .'/'. $imageName)) {
            $_SESSION['error'] = "file errors";
            redirect_to('admin/add_user');
        }
        $image = $imageName;

    } else {
        $_SESSION['error'] = $fileUploadErrors[$error];
        redirect_to('admin/add_user');
    }
    $privilegeIds = $_POST['privilege'];
//=========insert query============
    $insertQuery = "INSERT INTO tbl_users(name,username,email,password,image,created_at,updated_at)
                  VALUES('$name','$username','$email','$password','$image','$cDate','$uDate')";
    $res = mysqli_query($connection, $insertQuery);
    $lastInsertId = mysqli_insert_id($connection);
    $increment = 0;
    if ($lastInsertId) {
        foreach ($privilegeIds as $id) {
            $inert = "INSERT INTO tbl_manage_privilege(user_id,privilege_id)VALUES('$lastInsertId','$id')";
            $rest = mysqli_query($connection, $inert);
            if ($rest == true) {
                $increment++;
            }
        }
        $_SESSION['success'] = "data was successfully inserted";
        redirect_to('admin/show_users');
    }


}


?>


<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Add User</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <?= messages(); ?>
                                    <div class="col-md-10">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group form-group-sm">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" placeholder="enter your name" id="name"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="username">User Name</label>
                                                <input type="text" name="username" placeholder="enter your username"
                                                       id="username" class="form-control">
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" placeholder="enter your email"
                                                       id="email" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-sm">
                                                        <label for="password">Password</label>
                                                        <input type="password" name="password"
                                                               placeholder="enter your password"
                                                               id="password" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-sm">
                                                        <label for="password_confirmation">Password Confirm</label>
                                                        <input type="password" name="password_confirmation"
                                                               placeholder="password confirmation"
                                                               id="password_confirmation" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="privilege">Privilege</label>
                                                <select name="privilege[]" multiple id="privilege_id" class="form-control">
                                                    <?php foreach ($privilegeResult as $privilege) : ?>
                                                        <option value="<?=$privilege['id']?>"> <?=$privilege['privilege_name']?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="change_image">Profile Picture</label>
                                                <input type="file" name="upload"
                                                       id="change_image" class="btn btn-default"></div>
                                            <div class="form-group form-group-sm">
                                                <button class="btn btn-success btn-sm">Add Record</button>
                                            </div>

                                        </form>

                                    </div>
                                    <div class="col-md-2">
                                        <img src="<?= base_url('public/icons/not_found.png') ?>"
                                             alt="image not selected" id="target_image" class="img-responsive"
                                             style="margin-top: 23px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
