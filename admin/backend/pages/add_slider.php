<?php


if (!empty($_POST) && !empty($_FILES) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'];
    if (empty($title)) {
        $_SESSION['error'] = 'title field is required';
        redirect_to('admin/add_slider');
    }
    $description = $_POST['description'];

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
    $uploadPath = root_path('public/images/slider');


    if ($error == 0) {
        if (!move_uploaded_file($tmpName, $uploadPath . '/' . $imageName)) {
            $_SESSION['error'] = "file errors";
            redirect_to('admin/add_slider');
        }
        $image = $imageName;

    } else {
        $_SESSION['error'] = $fileUploadErrors[$error];
        redirect_to('admin/add_slider');
    }

    $insertQuery = "INSERT INTO tbl_sliders(title,description,image)
                  VALUES('$title','$description','$image')";
    $res = mysqli_query($connection, $insertQuery);
    $lastInsertId = mysqli_insert_id($connection);
    $increment = 0;
    if ($lastInsertId) {
        $_SESSION['success'] = "data was successfully inserted";
        redirect_to('admin/show_slider');
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
                        <h2>Add Slider</h2>
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
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="form-group form-group-sm">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" placeholder="enter title" id="title"
                                               class="form-control">
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="change_image">Image</label>
                                        <input type="file" name="upload"
                                               id="image" class="btn btn-default"></div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <button class="btn btn-success btn-sm">Add Record</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
