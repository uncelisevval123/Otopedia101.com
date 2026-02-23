<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Otopedia101.com</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- FANCYBOX (GALERİ OKLARI İÇİN) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css">
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/videolar.css">
<link rel="stylesheet" href="css/makaleler.css">
<link rel="stylesheet" href="css/makaleler-detay.css">
<link rel="stylesheet" href="css/hakkımızda.css">
</head>
<body>

<!-- Page Preloder -->
<div id="preloder">
    <div class="loader"></div>
</div>

<!-- Header Section Begin -->
<header class="navbar-area">
  <div class="nav-container">

    <div class="logo">
      <a href="index.php">OTOPEDIA101</a>
    </div>

    <nav class="nav-menu">
      <ul>
        <li><a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Anasayfa</a></li>
        <li><a href="videolar.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'videolar.php') ? 'active' : ''; ?>">Videolar</a></li>
        <li><a href="makaleler.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'makaleler.php') ? 'active' : ''; ?>">Makaleler</a></li>
        <li><a href="hakkımızda.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'hakkımızda.php') ? 'active' : ''; ?>">Hakkımda</a></li>
      </ul>
    </nav>

  </div>
</header>