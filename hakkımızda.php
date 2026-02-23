<!DOCTYPE html>
<html lang="tr">

<?php include "header.php"; ?>

<!-- HEADER -->
<header class="navbar-area">
  <div class="nav-container">
    <div class="logo">
      <a href="index.php">OTOPEDIA101</a>
    </div>
    <nav class="nav-menu">
      <ul id="navList">
        <li><a href="index.php" class="active">Anasayfa</a></li>
        <li><a href="videolar.php">Videolar</a></li>
        <li><a href="makaleler.php">Makaleler</a></li>
        <li><a href="hakkımızda.php">Hakkımda</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- HERO -->
<section class="about-hero">
  <div class="container">
    <h1>Otopedia101 Hakkında</h1>
    <p>Otomobil dünyasına tarafsız, sade ve gerçek içerikler.</p>
  </div>
</section>

<!-- ABOUT CONTENT -->
<section class="about-content">
  <div class="container">
    <div class="about-block">
      <h2>Biz Kimiz?</h2>
      <p>Otopedia; otomobil incelemeleri, kullanıcı deneyimleri ve teknik içerikleri sade bir dille sunmak amacıyla kurulmuş bağımsız bir otomotiv platformudur.</p>
    </div>
    <div class="row about-split">
      <div class="col-md-6">
        <h3>Misyonumuz</h3>
        <p>Otomobil almayı düşünen herkese doğru, tarafsız ve anlaşılır bilgiler sunmak.</p>
      </div>
      <div class="col-md-6">
        <h3>Vizyonumuz</h3>
        <p>Türkiye’nin en güvenilir dijital otomobil rehberi olmak.</p>
      </div>
    </div>
    <div class="about-highlight">
      <h3>Neden Otopedia?</h3>
      <ul>
        <li>Gerçek kullanıcı odaklı içerikler</li>
        <li>Abartısız, sade anlatım</li>
        <li>Video + makale destekli incelemeler</li>
        <li>Reklam değil deneyim</li>
      </ul>
    </div>
  </div>
</section>

<!-- FOOTER -->

<?php include "footer.php"; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script>
const toggle = document.getElementById("searchToggle");
const searchBox = document.getElementById("searchBox");
const input = document.getElementById("searchInput");

toggle.addEventListener("click", () => {
  searchBox.classList.toggle("active");
  if (searchBox.classList.contains("active")) input.focus();
});
</script>

</body>
</html>


