<!DOCTYPE html>
<html lang="en">

<head>
  <title>Employee Task Management System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/bootstrap.theme.min.css">
  <link rel="stylesheet" href="./assets/bootstrap-datepicker/css/datepicker.css">
  <link rel="stylesheet" href="./assets/bootstrap-datepicker/css/datepicker-custom.css">
  <link rel="stylesheet" href="./assets/css/custom.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="./assets/css/responsive.css?v=<?php echo time(); ?>">

  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/js/bootstrap.min.js"></script>
  <script src="./assets/js/custom.js?v=<?php echo time(); ?>"></script>
  <script src="./assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script src="./assets/bootstrap-datepicker/js/datepicker-custom.js"></script>

  <link rel="icon" href="./assets/img/icon_x48.png" />
  <link rel="apple-touch-icon" href="./assets/img/icon_x512.png" />
  <meta name="theme-color" content="#ffffff" />
  <link rel="manifest" href="manifest.json">

  <script type="text/javascript">
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('./assets/js/service-workers.js').then(reg => console.log("Service Worker: Registered", reg)).catch(err => console.log("Service Worker: Error", err));
    }
  </script>

  <script type="text/javascript">
    /* delete function confirmation  */
    function check_delete() {
      var check = confirm('Are you sure you want to delete this?');
      if (check) {
        return true;
      } else {
        return false;
      }
    }
  </script>
</head>

<body>

  <nav class="navbar navbar-inverse sidebar navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="task-info.php"><span style="color: #5bcad9; font-weight: bold;">Inluxi ETMS</span></a>
      </div>

      <?php
      if ($user_role == 1) {
      ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-nav-custom">
            <li <?php if ($page_name == "Task_Info") {
                  echo "class=\"active\"";
                } ?>><a href="task-info.php">Task Mangement<span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-tasks"></span></a></li>
            <li <?php if ($page_name == "Attendance") {
                  echo "class=\"active\"";
                } ?>><a href="attendance-info.php">Attendance <span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-calendar"></span></a></li>
            <li <?php if ($page_name == "Admin") {
                  echo "class=\"active\"";
                } ?>><a href="manage-admin.php">Administration<span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>
            <li <?php if ($page_name == "Daily-Task-Report") {
                  echo "class=\"active\"";
                } ?>><a href="daily-task-report.php">Task Report<span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-file"></span></a></li>
            <li <?php if ($page_name == "Leave") {
                  echo "class=\"active\"";
                } ?>>
              <a href="leave-info.php">
                Leave
                <span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-comment"></span>
              </a>
            </li>
            <li <?php if ($page_name == "Daily-Attennce-Report") {
                  echo "class=\"active\"";
                } ?>><a href="daily-attendance-report.php">Attendance Report<span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-file"></span></a></li>
            <li><a href="?logout=logout">Logout<span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a></li>
          </ul>
        </div>
      <?php
      } else if ($user_role == 2) {

      ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-nav-custom">
            <li <?php if ($page_name == "Task_Info") {
                  echo "class=\"active\"";
                } ?>>
              <a href="task-info.php">
                Task Mangement
                <span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-tasks"></span>
              </a>
            </li>
            <li <?php if ($page_name == "Attendance") {
                  echo "class=\"active\"";
                } ?>>
              <a href="attendance-info.php">
                Attendance
                <span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-calendar"></span>
              </a>
            </li>
            <li <?php if ($page_name == "Leave") {
                  echo "class=\"active\"";
                } ?>>
              <a href="leave-info.php">
                Leave
                <span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-comment"></span>
              </a>
            </li>
            <li><a href="?logout=logout">Logout<span style="font-size:16px; color:#5bcad9;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a></li>
          </ul>
        </div>

      <?php

      }

      ?>

    </div>
  </nav>



  <div class="main">