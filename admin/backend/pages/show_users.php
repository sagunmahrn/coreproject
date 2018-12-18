<?php
$query = "SELECT tbl_users.*,GROUP_CONCAT(tbl_privileges.privilege_name SEPARATOR ',') as privilege FROM tbl_users 
LEFT JOIN tbl_manage_privilege ON tbl_users.id=tbl_manage_privilege.user_id
LEFT JOIN tbl_privileges ON tbl_manage_privilege.privilege_id=tbl_privileges.id
GROUP BY tbl_users.id";

//=============update user status=========
$result = mysqli_query($connection, $query);
if (isset($_POST['active']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $criteria = $_POST['criteria'];
    $status = 0;
    $query = "UPDATE tbl_users SET status='$status' WHERE id=$criteria";
    $result = mysqli_query($connection, $query);
    if ($result == true) {
        $_SESSION['success'] = 'Successfully updated';
        redirect_to('admin/show_users');
    } else {
        $_SESSION['error'] = 'Error';
        redirect_to('admin/show_users');
    }
}

if (isset($_POST['inactive']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $criteria = $_POST['criteria'];
    $status = 1;
    $query = "UPDATE tbl_users SET status='$status' WHERE id=$criteria";
    $result = mysqli_query($connection, $query);
    if ($result == true) {
        $_SESSION['success'] = 'Successfully updated';
        redirect_to('admin/show_users');
    } else {
        $_SESSION['error'] = 'Error';
        redirect_to('admin/show_users');
    }
}
//=========delete user=====
if (!empty($_POST) && isset($_POST['delete_user'])&& $_SERVER['REQUEST_METHOD']== "POST") {
    $criteria = $_POST['criteria'];
    $deletePriQuery ="DELETE FROM tbl_manage_privilege WHERE user_id=". $criteria;
    $delRes = mysqli_query($connection, $deletePriQuery);
    if ($delRes == true) {
        $selectQue = "SELECT * FROM tbl_users WHERE id=". $criteria;
        $getRes = mysqli_query($connection, $selectQue);
        $fetData = mysqli_fetch_assoc($getRes);
        $fileName = $fetData['image'];
        $fileRemovePath = root_path('public/images/users/'. $fileName);
        if (file_exists($fileRemovePath)) {
            unlink($fileRemovePath);
        }
        $deleteUserQ = "DELETE FROM tbl_users WHERE id=" . $criteria;
        $delUserRes = mysqli_query($connection,$deleteUserQ);
        if($delUserRes==true){
            $_SESSION['success']='data was successfully deleted';
            redirect_to('admin/show_users');
        } else{
            $_SESSION['success']='error data while deleted';
            redirect_to('admin/show_users');
        }
    }
}


if (!empty($_POST) && isset($_POST['edit_user'])&& $_SERVER['REQUEST_METHOD']== "POST") {
    $criteria = $_POST['criteria'];
    $selectQue = "SELECT * FROM tbl_users WHERE id=" . $criteria;
    $getRes = mysqli_query($connection, $selectQue);
    $fetUserData = mysqli_fetch_assoc($getRes);
}
if(!empty($_POST)&& isset($_POST['update_user_action'])&& $_SERVER['REQUEST_METHOD']== "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $criteria = $_POST['criteria'];
    $uDate = date_time();

    if (!empty($_FILES['upload']['name'])){
        $selectQue = "SELECT * FROM tbl_users WHERE id=" . $criteria;
        $getRes = mysqli_query($connection, $selectQue);
        $fetData = mysqli_fetch_assoc($getRes);
        $fileName = $fetData['image'];
        $fileRemovePath = root_path('public/images/users/' . $fileName);
        if (file_exists($fileRemovePath)) {
            unlink($fileRemovePath);
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
                if (!move_uploaded_file($tmpName, $uploadPath . '/' . $imageName)) {
                    $_SESSION['error'] = "file errors";
                    redirect_to('admin/add_user');
                }
                $image = $imageName;

            } else {
                $_SESSION['error'] = $fileUploadErrors[$error];
                redirect_to('admin/add_user');
            }

            $updateQuery = "UPDATE tbl_users SET name='$name',username='$username',email='$email',image='$image' WHERE id=". $criteria;
            $upRes = mysqli_query($connection, $updateQuery);
            if($upRes == true) {
                $_SESSION['success'] = 'data was successfully updated';
                redirect_to('admin/show_users');

            }else {
                $_SESSION['error'] = 'error ';
                redirect_to('admin/show_users');
                }

        }
    }


    else{
        $updateQuery= "UPDATE tbl_users SET name='$name',username='$username',email='$email'WHERE id=".$criteria;
        $upResult=mysqli_query($connection,$updateQuery);
        if($upResult==true){
            $_SESSION['success']='data was successfully updated';
            redirect_to('admin/show_users');

        }
        else{
            $_SESSION['error']='update error';
            redirect_to('admin/show_users');
                }
    }
    //=================upload image=========


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
                        <h2>Show Users</h2>
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

                            <?php if(isset($_POST['edit_user'])) :?>
                                    <div class="row">
                                        <?= messages(); ?>
                                        <div class="col-md-10">
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="criteria" value="<?=$fetUserData['id']?>">
                                                <div class="form-group form-group-sm">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="name" value="<?=$fetUserData['name']?>" placeholder="enter your name" id="name"
                                                           class="form-control">
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="username">User Name</label>
                                                    <input type="text" name="username" value="<?=$fetUserData['username']?>" placeholder="enter your username"
                                                           id="username" class="form-control">
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="email">Email</label>
                                                    <input type="text" name="email" value="<?=$fetUserData['email']?>" placeholder="enter your email"
                                                           id="email" class="form-control">
                                                </div>

                                                <div class="form-group form-group-sm">
                                                    <label for="change_image">Profile Picture</label>
                                                    <input type="file" name="upload"
                                                           id="change_image" class="btn btn-default"></div>
                                                <div class="form-group form-group-sm">
                                                    <button  name="update_user_action" class="btn btn-primary btn-sm">Update Record</button>
                                                </div>

                                            </form>

                                        </div>
                                        <div class="col-md-2">
                                            <img src="<?= base_url('public/images/users/'.$fetUserData['image'])?>"
                                                 alt="image not selected" id="target_image" class="img-responsive"
                                                 style="margin-top: 23px;">
                                        </div>
                                    </div>
                            <?php else:?>


                        <div class="row">
                            <div class="col-md-12">
                                <?= messages(); ?>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Privilege</th>
                                        <th>Status</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($result as $key => $users) : ?>
                                        <tr>
                                            <td><?=++$key?></td>
                                            <td><?=$users['name']?></td>
                                            <td><?=$users['username']?></td>
                                            <td><?=$users['email']?></td>
                                            <td><?=$users['privilege']?></td>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="criteria"
                                                           value="<?= $users['id'] ?>">
                                                    <?php if ($users['status'] == 1) : ?>
                                                        <button type="submit" name="active"
                                                                class="btn btn-success btn-xs">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" name="inactive"
                                                                class="btn btn-warning btn-xs">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </form>

                                            </td>
                                            <td>
                                                <img src="<?=base_url('public/images/users/'.$users['image'])?>" alt="image not found" width="30">
                                            </td>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="criteria"
                                                           value="<?= $users['id'] ?>">
                                                    <button name="edit_user" class="btn btn-primary btn-xs">
                                                        Edit
                                                    </button>
                                                    <button name="delete_user" class="btn btn-danger btn-xs">
                                                        Delete
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
