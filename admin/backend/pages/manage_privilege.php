<?php
//==========select data============
$select = "SELECT * FROM tbl_privileges ORDER BY id DESC";
$result = mysqli_query($connection, $select);

if (isset($_POST['create_privilege']) && $_SERVER['REQUEST_METHOD'] == "POST") {
  $privilegeName = $_POST['privilege_name'];
    if (empty($privilegeName)) {
        $_SESSION['error'] = 'Privilege field is required';
        redirect_to('admin/manage_privilege');
    }
  //  =======check unique key ========
    $select = "SELECT * FROM tbl_privileges WHERE privilege_name='$privilegeName'";
    $pre = mysqli_query($connection, $select);
    $row = mysqli_num_rows($pre);
    if ($row > 0) {
        $_SESSION['error'] = 'Privilege name is already exist';
        redirect_to('admin/manage_privilege');
    }

    $time = date_time();

    $insertQuery = "INSERT INTO tbl_privileges(privilege_name,created_at,updated_at)
                    VALUES('$privilegeName','$time','$time')";
    $prepareQuery = mysqli_query($connection, $insertQuery);
    if ($prepareQuery == true) {
        $_SESSION['success'] = 'Successfully inserted';
        redirect_to('admin/manage_privilege');
    } else {
        $_SESSION['error'] = 'Error';
        redirect_to('admin/manage_privilege');
    }
}

//===========update privilege status =========
if (isset($_POST['active']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $criteria = $_POST['criteria'];
    $status = 0;
    $query = "UPDATE tbl_privileges SET status='$status' WHERE id=$criteria";
    $result = mysqli_query($connection, $query);
    if ($result == true) {
        $_SESSION['success'] = 'Successfully updated';
        redirect_to('admin/manage_privilege');
    } else {
        $_SESSION['error'] = 'Error';
        redirect_to('admin/manage_privilege');
    }
}

if (isset($_POST['inactive']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $criteria = $_POST['criteria'];
    $status = 1;
    $query = "UPDATE tbl_privileges SET status='$status' WHERE id=$criteria";
    $result = mysqli_query($connection, $query);
    if ($result == true) {
        $_SESSION['success'] = 'Successfully updated';
        redirect_to('admin/manage_privilege');
    } else {
        $_SESSION['error'] = 'Error';
        redirect_to('admin/manage_privilege');
    }
}

//=============delete privilege ============
if (isset($_POST['delete_privilege']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $criteria = $_POST['criteria'];
    $query = "DELETE FROM tbl_privileges WHERE id=" . $criteria;
    $result = mysqli_query($connection, $query);
    if ($result == true) {
        $_SESSION['success'] = 'Successfully deleted';
        redirect_to('admin/manage_privilege');
    } else {
        $_SESSION['error'] = 'Error';
        redirect_to('admin/manage_privilege');
    }
}

//===========edit privilege ============
if (isset($_POST['edit_privilege']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $criteria = $_POST['criteria'];
    $select = "SELECT * FROM tbl_privileges WHERE id=" . $criteria;
    $result = mysqli_query($connection, $select);
    $privilegeData = mysqli_fetch_assoc($result);

}

//==========update privilege action=======

if (isset($_POST['update_privilege_action']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $criteria = $_POST['criteria'];
    $privilegeName =$_POST['privilege_name'];
    $query = "UPDATE tbl_privileges SET privilege_name='$privilegeName' WHERE id=$criteria";
    $result = mysqli_query($connection, $query);
    if ($result == true) {
        $_SESSION['success'] = 'Successfully updated';
        redirect_to('admin/manage_privilege');
    } else {
        $_SESSION['error'] = 'Error';
        redirect_to('admin/manage_privilege');
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
                        <h2>Manage Privilege </h2>
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
                        <?php if (isset($_POST['edit_privilege'])): ?>
                            <!--===========edit ============-->
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <form action="" method="post">
                                            <input type="hidden" name="criteria" value="<?= $privilegeData['id'] ?>">
                                            <div class="col-md-6" style="padding-right: 1px;">
                                                <div class="form-group form-group-sm">
                                                    <input type="text" name="privilege_name"
                                                           value="<?= $privilegeData['privilege_name'] ?>"
                                                           placeholder="Enter privilege" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="padding-left: 0;">
                                                <div class="form-group">
                                                    <button name="update_privilege_action"
                                                            class="btn btn-success btn-sm">
                                                        Update Info
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!--==============end edit=====-->

                            <div class="row">
                                <?= messages(); ?>
                                <div class="col-md-12">

                                    <div class="row">
                                        <form action="" method="post">
                                            <div class="col-md-6" style="padding-right: 1px;">
                                                <div class="form-group form-group-sm">
                                                    <input type="text" name="privilege_name"
                                                           placeholder="Enter privilege" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="padding-left: 0;">
                                                <div class="form-group">
                                                    <button name="create_privilege" class="btn btn-success btn-sm">
                                                        Create Privilege
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>S.n</th>
                                            <th>Privilege</th>
                                            <th>Status</th>
                                            <th>Created at</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($result as $key => $privilege) : ?>
                                            <tr>
                                                <td><?= ++$key ?></td>
                                                <td><?= ucfirst($privilege['privilege_name']) ?></td>
                                                <td>
                                                    <form action="" method="post">
                                                        <input type="hidden" name="criteria"
                                                               value="<?= $privilege['id'] ?>">
                                                        <?php if ($privilege['status'] == 1) : ?>
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
                                                <td><?= $privilege['created_at'] ?></td>
                                                <td>
                                                    <form action="" method="post">
                                                        <input type="hidden" name="criteria"
                                                               value="<?= $privilege['id'] ?>">
                                                        <button name="edit_privilege" class="btn btn-primary btn-xs">
                                                            Edit
                                                        </button>
                                                        <button name="delete_privilege" class="btn btn-danger btn-xs">
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

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

