<?php
// auth check
$user_id = $user_name = $security_key = $user_role = NULL;

if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] != '') {
    $user_id = $_SESSION['admin_id'];
}
if (isset($_SESSION['name']) && $_SESSION['name'] != '') {
    $user_name = $_SESSION['name'];
}
if (isset($_SESSION['security_key']) && $_SESSION['security_key'] != '') {
    $security_key = $_SESSION['security_key'];
}
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != '') {
    $user_role = $_SESSION['user_role'];
}
