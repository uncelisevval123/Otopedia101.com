<?php
/**
 * ============================================
 * OTOPEDIA MAKALE DÜZENLE
 * ============================================
 * Mevcut makaleyi düzenleme sayfası
 */

session_start();

// Yetkisiz erişimi engelle
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

// ID kontrolü
$id = $_GET["id"] ?? null;
if (!$id) {
    header("Location: makaleliste.php");
    exit;
}

// Makaleyi getir
try {
    $sorgu = $db->prepare("SELECT * FROM makaleler WHERE id=?");
    $sorgu->execute([$id]);
    $makale = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$makale) {
        header("Location: makaleliste.php");
        exit;
    }
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}

// Form gönderildiğinde
$mesaj = "";
$mesajTip = "";

if ($_POST) {
    $baslik = trim($_POST["baslik"]);
    $icerik = trim($_POST["icerik"]);
    $foto   = trim($_POST["foto"]);

    // Validasyon
    if (empty($baslik)) {
        $mesaj = "Başlık boş olamaz!";
        $mesajTip = "danger";
    } elseif (empty($icerik)) {
        $mesaj = "İçerik boş olamaz!";
        $mesajTip = "danger";
    } else {
        try {
            $guncelle = $db->prepare(
                "UPDATE makaleler SET baslik=?, icerik=?, foto=?, guncelleme_tarihi=NOW() WHERE id=?"
            );
            $guncelle->execute([$baslik, $icerik, $foto, $id]);

            header("Location: makaleliste.php?success=updated");
            exit;
        } catch (PDOException $e) {
            $mesaj = "Güncelleme hatası: " . $e->getMessage();
            $mesajTip = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Makale Düzenle - Otopedia Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #ff5c5c;
            --secondary-color: #ff7b00;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }
        
        .page-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .page-header h2 {
            margin: 0;
            color: #333;
            font-weight: 700;
        }
        
        .page-header .icon {
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 92, 92, 0.15);
        }
        
        .btn-group-custom {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 92, 92, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .btn-back {
            background: transparent;
            border: 2px solid #6c757d;
            color: #6c757d;
        }
        
        .btn-back:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .page-header h2 {
                font-size: 1.5rem;
            }
            
            .btn-group-custom {
                width: 100%;
            }
            
            .btn-group-custom .btn {
                flex: 1;
            }
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- Geri Dön Butonu -->
    <div class="mb-3">
        <a href="makaleliste.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    
    <!-- Ana Kart -->
    <div class="page-card">
        
        <!-- Sayfa Başlığı -->
        <div class="page-header">
            <i class="fas fa-edit icon"></i>
            <h2>Makale Düzenle</h2>
        </div>
        
        <!-- Mesaj Gösterimi -->
        <?php if ($mesaj): ?>
            <div class="alert alert-<?= $mesajTip ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?= $mesajTip === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= htmlspecialchars($mesaj) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Form -->
        <form method="post">
            
            <!-- Başlık -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-heading"></i> Makale Başlığı
                </label>
                <input type="text" 
                       class="form-control" 
                       name="baslik" 
                       value="<?= htmlspecialchars($makale["baslik"]) ?>"
                       placeholder="Örn: Otomobil Bakım Rehberi"
                       required>
            </div>
            
            <!-- Fotoğraf URL -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-image"></i> Fotoğraf URL
                </label>
                <input type="text" 
                       class="form-control" 
                       name="foto" 
                       value="<?= htmlspecialchars($makale["foto"] ?? '') ?>"
                       placeholder="https://example.com/image.jpg">
                <small class="text-muted">Fotoğraf linkini buraya yapıştırın (opsiyonel)</small>
            </div>
            
            <!-- İçerik -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-align-left"></i> Makale İçeriği
                </label>
                <textarea class="form-control" 
                          name="icerik" 
                          rows="12"
                          placeholder="Makale içeriğini buraya yazın..."
                          required><?= htmlspecialchars($makale["icerik"]) ?></textarea>
            </div>
            
            <!-- Butonlar -->
            <div class="btn-group-custom">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Değişiklikleri Kaydet
                </button>
                <a href="makaleliste.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> İptal
                </a>
            </div>
            
        </form>
        
    </div>
    
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Form Validasyonu -->
<script>
    // Form gönderilmeden önce onay
    document.querySelector('form').addEventListener('submit', function(e) {
        const baslik = document.querySelector('input[name="baslik"]').value.trim();
        const icerik = document.querySelector('textarea[name="icerik"]').value.trim();
        
        if (!baslik || !icerik) {
            e.preventDefault();
            alert('Başlık ve içerik alanları doldurulmalıdır!');
            return false;
        }
        
        if (!confirm('Değişiklikleri kaydetmek istediğinizden emin misiniz?')) {
            e.preventDefault();
            return false;
        }
    });
</script>

</body>
</html>