<?php

ob_start();

if (session_id() == '') {
  session_start();
}else if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require 'classes/admin_class.php';

$obj_admin = new Admin_Class();

if (isset($_GET['logout'])) {
  $obj_admin->admin_logout();
}
