<?php
//===========required config file========
require_once ('../config/config.php');
session_destroy();
redirect_to('admin/login');
exit();
