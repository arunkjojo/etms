<!DOCTYPE html>
<html lang="en">

<head>
  <title>Employee Time Management System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/bootstrap.theme.min.css">

  <link rel="stylesheet" href="./assets/css/custom.css">
  <link rel="stylesheet" href="./assets/css/responsive.css">

  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/js/bootstrap.min.js"></script>
  <script src="./assets/js/custom.js?v=<?php echo time(); ?>"></script>


  <link rel="icon" href="./assets/img/icon_x48.png" />
  <link rel="apple-touch-icon" href="./assets/img/icon_x512.png" />
  <meta name="theme-color" content="#ffffff" />
  <link rel="manifest" href="manifest.json">

  <script type="text/javascript">
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('./assets/js/service-workers.js').then(reg => console.log("Service Worker: Registered", reg)).catch(err => console.log("Service Worker: Error", err));
    };
  </script>
</head>

<body>

  <div class="main">