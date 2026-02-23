<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$hata = '';
$basarili = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baslik     = trim($_POST['baslik']     ?? '');
    $alt_baslik = trim($_POST['alt_baslik'] ?? '');
    $ozet       = trim($_POST['ozet']       ?? '');
    $icerik     = trim($_POST['icerik']     ?? '');
    $kategori   = trim($_POST['kategori']   ?? '');
    $resim_url  = trim($_POST['resim_url']  ?? '');
    $sayfa      = trim($_POST['sayfa']      ?? '');

    // Validasyon
    if (empty($baslik)) {
        $hata = 'Başlık boş olamaz!';
    } elseif (empty($icerik)) {
        $hata = 'İçerik boş olamaz!';
    } elseif (empty($kategori)) {
        $hata = 'Kategori boş olamaz!';
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO makaleler (baslik, alt_baslik, ozet, icerik, kategori, resim_url, sayfa) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$baslik, $alt_baslik, $ozet, $icerik, $kategori, $resim_url, $sayfa]);

            header("Location: makaleliste.php?durum=eklendi");
            exit;
        } catch (PDOException $e) {
            $hata = 'Veritabanı hatası: ' . $e->getMessage();
        }
    }
}


$kategoriler = ['Test Sürüşü', 'Süper Spor', 'Teknoloji', 'Karşılaştırma', 'Haber', 'Galeri'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Makale Ekle - Otopedia Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #ff5c5c;
            --secondary: #ff7b00;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.10);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 1.8rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .page-header .icon {
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.4rem;
            font-size: 0.92rem;
        }

        .form-control,
        .form-select,
        textarea {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 0.7rem 1rem;
            transition: border-color 0.25s, box-shadow 0.25s;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 92, 92, 0.15);
            outline: none;
        }

        textarea {
            min-height: 220px;
            resize: vertical;
        }

        /* Resim URL önizleme alanı */
        #resimOnizleme {
            display: none;
            margin-top: 0.75rem;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #e0e0e0;
            background: #f8f9fa;
            text-align: center;
        }

        #resimOnizleme img {
            max-height: 240px;
            max-width: 100%;
            object-fit: contain;
            padding: 0.5rem;
        }

        /* Sayfa adı yardım metni */
        .sayfa-hint {
            font-size: 0.8rem;
            color: #888;
            margin-top: 0.25rem;
        }

        /* Butonlar */
        .btn {
            border-radius: 10px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            border: none;
            transition: all 0.25s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 16px rgba(255, 92, 92, 0.35);
            background: linear-gradient(135deg, #ff4444, #ff6a00);
            color: white;
        }

        .btn-back {
            background: transparent;
            border: 2px solid #6c757d;
            color: #6c757d;
        }

        .btn-back:hover {
            background: #6c757d;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            color: white;
        }

        /* Zorunlu yıldız */
        .req { color: #dc3545; }

        /* Karakter sayacı */
        .char-count {
            font-size: 0.78rem;
            color: #aaa;
            text-align: right;
            margin-top: 3px;
        }
        .char-count.warn { color: #ff7b00; }
        .char-count.over  { color: #dc3545; }
    </style>
</head>
<body>

<div class="container" style="max-width:860px;">

    <!-- GERİ DÖN -->
    <div class="mb-3">
        <a href="makaleliste.php" class="btn btn-back">
            <i class="fas fa-arrow-left me-1"></i> Makale Listesine Dön
        </a>
    </div>

    <!-- ANA KART -->
    <div class="page-card">

        <div class="page-header">
            <i class="fas fa-plus-circle icon"></i>
            <h2>Yeni Makale Ekle</h2>
        </div>

        <!-- HATA MESAJI -->
        <?php if ($hata): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($hata) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" id="makaleForm">

            <!-- BAŞLIK + ALT BAŞLIK yan yana -->
            <div class="row g-3 mb-3">
                <div class="col-md-7">
                    <label class="form-label">
                        <i class="fas fa-heading me-1"></i> Makale Başlığı <span class="req">*</span>
                    </label>
                    <input type="text"
                           name="baslik"
                           id="baslikInput"
                           class="form-control"
                           maxlength="150"
                           required
                           placeholder="Örn: BMW M4 Competition İncelemesi"
                           value="<?= htmlspecialchars($_POST['baslik'] ?? '') ?>">
                    <div class="char-count" id="baslikCount">0 / 150</div>
                </div>
                <div class="col-md-5">
                    <label class="form-label">
                        <i class="fas fa-text-height me-1"></i> Alt Başlık
                    </label>
                    <input type="text"
                           name="alt_baslik"
                           class="form-control"
                           maxlength="120"
                           placeholder="Örn: Saf performans ve agresif M karakteri"
                           value="<?= htmlspecialchars($_POST['alt_baslik'] ?? '') ?>">
                </div>
            </div>

            <!-- ÖZET -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-align-left me-1"></i> Kısa Özet
                </label>
                <input type="text"
                       name="ozet"
                       class="form-control"
                       maxlength="255"
                       placeholder="Makale hakkında kısa açıklama (liste görünümünde görünür)"
                       value="<?= htmlspecialchars($_POST['ozet'] ?? '') ?>">
            </div>

            <!-- İÇERİK -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-file-alt me-1"></i> Makale İçeriği <span class="req">*</span>
                </label>
                <textarea name="icerik"
                          class="form-control"
                          required
                          placeholder="Makale içeriğini buraya yazın..."><?= htmlspecialchars($_POST['icerik'] ?? '') ?></textarea>
            </div>

            <!-- KATEGORİ + SAYFA yan yana -->
            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label">
                        <i class="fas fa-folder me-1"></i> Kategori <span class="req">*</span>
                    </label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Kategori Seçin --</option>
                        <?php foreach ($kategoriler as $kat): ?>
                            <option value="<?= htmlspecialchars($kat) ?>"
                                <?= (($_POST['kategori'] ?? '') === $kat) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label">
                        <i class="fas fa-file-code me-1"></i> Sayfa (PHP Dosya Adı)
                    </label>
                    <div class="input-group">
                        <input type="text"
                               name="sayfa"
                               class="form-control"
                               placeholder="makale1.php"
                               value="<?= htmlspecialchars($_POST['sayfa'] ?? '') ?>">
                    </div>
                    <p class="sayfa-hint"><i class="fas fa-info-circle"></i> Makale detay sayfasının adı (örn: makale5.php). Boş bırakılabilir.</p>
                </div>
            </div>

            <!-- RESİM URL -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-image me-1"></i> Kapak Resmi URL
                </label>
                <input type="url"
                       name="resim_url"
                       id="resimUrlInput"
                       class="form-control"
                       placeholder="https://example.com/resim.jpg"
                       value="<?= htmlspecialchars($_POST['resim_url'] ?? '') ?>">
                <p class="sayfa-hint"><i class="fas fa-info-circle"></i> Tam URL girin. Otomatik önizleme gösterilir.</p>

                <!-- Önizleme -->
                <div id="resimOnizleme">
                    <img id="resimPreviewImg" src="" alt="Resim Önizleme">
                </div>
            </div>

            <!-- BUTONLAR -->
            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Makaleyi Kaydet
                </button>
                <a href="makaleliste.php" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> İptal
                </a>
            </div>

        </form>

    </div><!-- /.page-card -->
</div><!-- /.container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* ---- Başlık karakter sayacı ---- */
    const baslikInput = document.getElementById('baslikInput');
    const baslikCount = document.getElementById('baslikCount');

    function updateCount() {
        const len = baslikInput.value.length;
        baslikCount.textContent = len + ' / 150';
        baslikCount.className = 'char-count' + (len > 130 ? ' warn' : '') + (len >= 150 ? ' over' : '');
    }
    baslikInput.addEventListener('input', updateCount);
    updateCount();

    /* ---- Resim URL önizleme ---- */
    const resimUrlInput = document.getElementById('resimUrlInput');
    const resimOnizleme = document.getElementById('resimOnizleme');
    const resimPreviewImg = document.getElementById('resimPreviewImg');

    function resimOnizle() {
        const url = resimUrlInput.value.trim();
        if (url) {
            resimPreviewImg.src = url;
            resimOnizleme.style.display = 'block';
            resimPreviewImg.onerror = function () {
                resimOnizleme.style.display = 'none';
            };
        } else {
            resimOnizleme.style.display = 'none';
        }
    }

    resimUrlInput.addEventListener('blur', resimOnizle);
    resimUrlInput.addEventListener('input', function () {
        if (!this.value.trim()) resimOnizleme.style.display = 'none';
    });


    if (resimUrlInput.value.trim()) resimOnizle();

    /* ---- Sayfa adı otomatik doldur ---- */
    baslikInput.addEventListener('blur', function () {
        const sayfaInput = document.querySelector('input[name="sayfa"]');
        if (sayfaInput && !sayfaInput.value) {
           
        }
    });

    /* ---- Form gönderim onayı ---- */
    document.getElementById('makaleForm').addEventListener('submit', function (e) {
        if (!confirm('Makaleyi kaydetmek istediğinizden emin misiniz?')) {
            e.preventDefault();
        }
    });
</script>

</body>
</html>
