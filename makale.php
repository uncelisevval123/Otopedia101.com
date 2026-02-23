<!DOCTYPE html>
<html lang="tr">

<?php include "header.php"; ?>

<body>

<?php
require "db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: makaleler.php");
    exit;
}

$sorgu = $db->prepare("SELECT * FROM makaleler WHERE id = ?");
$sorgu->execute([$id]);
$makale = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$makale) {
    header("Location: makaleler.php");
    exit;
}
?>

<style>
.article-hero {
    position: relative;
    width: 100%;
    height: 500px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: flex-end;
    padding-bottom: 40px;
}

.article-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.75));
}

.article-hero .container {
    position: relative;
    z-index: 1;
}

.article-hero .article-tag {
    background: #e74c3c;
    color: #fff;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 12px;
}

.article-hero h1 {
    color: #fff;
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 10px;
}

.article-hero p {
    color: rgba(255,255,255,0.85);
    font-size: 18px;
    margin: 0;
}

.article-content {
    padding: 60px 0;
}

.article-content .lead {
    font-size: 20px;
    font-weight: 500;
    color: #333;
    margin-bottom: 24px;
}

.article-content p {
    font-size: 16px;
    line-height: 1.9;
    color: #555;
    margin-bottom: 20px;
}
</style>

<!-- ================= NAVBAR ================= -->
<header class="navbar-area">
  <div class="nav-container">
    <div class="logo">
      <a href="index.php">OTOPEDIA101</a>
    </div>

    <nav class="nav-menu">
      <ul>
        <li><a href="index.php">Anasayfa</a></li>
        <li><a href="videolar.php">Videolar</a></li>
        <li><a href="makaleler.php" class="active">Makaleler</a></li>
        <li><a href="hakkımızda.php">Hakkımda</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- ================= HERO ================= -->
<section class="article-hero" style="background-image:url('<?= htmlspecialchars($makale['resim_url']) ?>')">
  <div class="container">
    <span class="article-tag"><?= htmlspecialchars($makale['kategori']) ?></span>
    <h1><?= htmlspecialchars($makale['baslik']) ?></h1>
    <p><?= htmlspecialchars($makale['alt_baslik']) ?></p>
  </div>
</section>

<!-- ================= İÇERİK ================= -->
<section class="article-content">
  <div class="container">
    <div class="col-lg-8 mx-auto">

      <p class="lead"><?= htmlspecialchars($makale['ozet']) ?></p>

      <p><?= nl2br(htmlspecialchars($makale['icerik'])) ?></p>

      <a href="makaleler.php" class="btn btn-outline-secondary mt-4">← Makalelere Dön</a>

    </div>
  </div>
</section>

<!-- ================= FOOTER ================= -->
<?php include "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>