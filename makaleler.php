<!DOCTYPE html>
<html lang="tr">

<?php include "header.php"; ?>

<body>

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

<!-- ================= BREADCRUMB ================= -->
<div class="breadcrumb-option">
  <div class="container">
    <div class="text-center">
      <h2>Makaleler</h2>
    </div>
  </div>
</div>

<!-- ================= BLOG ================= -->
<section class="blog-section py-5">
  <div class="container">
    <div class="row">

      <!-- MAKALELER -->
      <div class="col-lg-9">
        <div class="row">

          <?php
          require "db.php";
          $sorgu = $db->query("SELECT * FROM makaleler");
          $makaleler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

          foreach ($makaleler as $makale): ?>

          <div class="col-md-6 mb-4 article-item">
            <div class="blog-card">
              <a href="makale.php?id=<?= $makale['id'] ?>" class="blog-img"
                 style="background-image:url('<?= htmlspecialchars($makale['resim_url']) ?>')">
                <span><?= htmlspecialchars($makale['kategori']) ?></span>
              </a>
              <div class="blog-content">
                <h5><?= htmlspecialchars($makale['baslik']) ?></h5>
                <p><?= htmlspecialchars($makale['ozet']) ?></p>
              </div>
            </div>
          </div>

          <?php endforeach; ?>

        </div>
      </div>

      <!-- SIDEBAR -->
      <div class="col-lg-3">
        <input type="text" id="articleSearch"
               class="form-control"
               placeholder="Makale ara...">
      </div>

    </div>
  </div>
</section>

<!-- ================= FOOTER ================= -->

<?php include "footer.php"; ?>

<!-- ================= JS ================= -->
<script>
  const searchInput = document.getElementById("articleSearch");
  const articles = document.querySelectorAll(".article-item");

  searchInput.addEventListener("keyup", function () {
    const value = this.value.toLowerCase();

    articles.forEach(article => {
      const text = article.innerText.toLowerCase();
      article.style.display = text.includes(value) ? "block" : "none";
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>