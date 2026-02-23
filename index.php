<?php
/* ================= YOUTUBE API ================= */
$apiKey    = "AIzaSyCz4tRrUXVs4AN18ilO1toCOpf0V7tG6Yw";
$channelId = "UChfY_S-Q7a_0NttbuLrfNEQ";

$url = "https://www.googleapis.com/youtube/v3/search?" . http_build_query([
    'key'        => $apiKey,
    'channelId'  => $channelId,
    'part'       => 'snippet',
    'order'      => 'date',
    'maxResults' => 1,
    'type'       => 'video'
]);

$videoId = null;
try {
    $response = @file_get_contents($url);
    if ($response !== false) {
        $data = json_decode($response, true);
        $videoId = $data['items'][0]['id']['videoId'] ?? null;
    }
} catch (Exception $e) {
    $videoId = null;
}

require "db.php";
?>

<!DOCTYPE html>
<html lang="tr">

<?php 
if (file_exists("header.php")) {
    include "header.php"; 
}
?>

<body>

<!-- PRELOADER -->
<div id="preloder">
  <div class="loader"></div>
</div>

<!-- HEADER -->
<header class="navbar-area">
  <div class="nav-container">
    <div class="logo">
      <a href="index.php">OTOPEDIA101</a>
    </div>
    <nav class="nav-menu">
      <ul>
        <li><a href="index.php" class="active">Anasayfa</a></li>
        <li><a href="videolar.php">Videolar</a></li>
        <li><a href="makaleler.php">Makaleler</a></li>
        <li><a href="hakkımızda.php">Hakkımda</a></li>
      </ul>
    </nav>
  </div>
</header>


<!-- HERO -->
<section class="hero">
  
  <div class="hero-bg">
    <?php if ($videoId): ?>
    <iframe
      src="https://www.youtube.com/embed/<?= $videoId ?>?autoplay=1&mute=1&loop=1&playlist=<?= $videoId ?>&controls=0&rel=0&playsinline=1&modestbranding=1&showinfo=0&vq=hd1080"
      allow="autoplay"
      frameborder="0"
      allowfullscreen>
    </iframe>
    <?php else: ?>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 100%; height: 100%;"></div>
    <?php endif; ?>
  </div>

  <div class="hero-overlay"></div>

  <div class="hero-content">
    <div class="form-box">
      <form class="ultra-form" action="https://formsubmit.co/otopedia101@gmail.com" method="POST">
        <h2>PREMİUM ARAÇ ÖNERİ FORMU</h2>

        <input type="hidden" name="_subject" value="Yeni Araç Önerisi">
        <input type="hidden" name="_captcha" value="false">
        <input type="hidden" name="_template" value="table">
        <input type="hidden" name="_next" value="https://otopedia101.page.gd/tesekkurler.html">

        <div class="form-grid">
          <input type="text" name="Ad_Soyad" placeholder="Ad Soyad" required>
          <input type="email" name="E-posta" placeholder="E-posta" required>
          <input type="text" name="Marka" placeholder="Marka" required>
          <input type="text" name="Model" placeholder="Model" required>
          <input type="text" name="Yıl" placeholder="Yıl" required>
          <input type="text" name="Vites" placeholder="Vites" required>
          <input type="text" name="Kilometre" placeholder="Kilometre" required>
          <input type="text" name="Yakıt" placeholder="Yakıt" required>
          <textarea name="Açıklama" placeholder="Açıklama" required></textarea>
        </div>

        <button type="submit">Gönder</button>
      </form>
    </div>
  </div>

</section>

<!-- MAKALELER -->
<section class="home-articles">
  <div class="container">
    <div class="section-title">
      <h2>Öne Çıkan Makaleler</h2>
      <p>Otomobil dünyasından seçilmiş özel içerikler</p>
    </div>

    <div class="row g-4">

      <?php
      $sorgu = $db->query("SELECT * FROM makaleler ORDER BY id DESC LIMIT 8");
      $makaleler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

      foreach ($makaleler as $makale): ?>

      <div class="col-lg-3 col-md-6">
        <a href="makale.php?id=<?= $makale['id'] ?>" class="article-card">
          <div class="article-img" style="background-image:url('<?= htmlspecialchars($makale['resim_url']) ?>')"></div>
          <div class="article-content">
            <span class="article-tag"><?= htmlspecialchars($makale['kategori']) ?></span>
            <h5><?= htmlspecialchars($makale['baslik']) ?></h5>
          </div>
        </a>
      </div>

      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- GALERİ -->
<section class="media-gallery">
  <div class="container">
    <div class="row align-items-center">

      <div class="col-lg-6">
        <?php if ($videoId): ?>
        <a data-fancybox href="https://www.youtube.com/watch?v=<?= $videoId ?>">
          <div class="video-thumbnail" style="position:relative;">
            <img src="https://img.youtube.com/vi/<?= $videoId ?>/maxresdefault.jpg" class="img-fluid rounded" alt="Video Thumbnail">
            <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:60px;color:#fff;">▶</span>
          </div>
        </a>
        <?php else: ?>
        <div class="video-thumbnail" style="background: #f0f0f0; padding: 100px; text-align: center; border-radius: 10px;"></div>
        <?php endif; ?>
      </div>

      <div class="col-lg-6">
        <div class="row g-3">
          <?php
          $galeri_sorgu = $db->query("SELECT * FROM galeri WHERE tur = 'resim' ORDER BY id DESC LIMIT 8");
          $galeriler = $galeri_sorgu->fetchAll(PDO::FETCH_ASSOC);

          if (count($galeriler) > 0):
            foreach ($galeriler as $g): ?>
            <div class="col-6">
              <a href="<?= htmlspecialchars($g['url']) ?>" data-fancybox="gallery">
                <img src="<?= htmlspecialchars($g['url']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($g['baslik']) ?>">
              </a>
            </div>
            <?php endforeach;
          else: ?>
            <div class="col-12">
              <p class="text-muted text-center">Henüz galeri görseli eklenmemiş.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<?php 
if (file_exists("footer.php")) {
    include "footer.php"; 
}
?>
</body>
</html>