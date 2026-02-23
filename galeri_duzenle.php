<?php
/**
 * ============================================
 * OTOPEDIA GALERİ DÜZENLE
 * ============================================
 * Mevcut galeri öğesini düzenleme sayfası
 */

session_start();

// Yetkisiz erişimi engelle
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$hata = '';
$basarili = '';

// ID kontrolü
if (!isset($_GET['id'])) {
    header("Location: galeri_liste.php");
    exit;
}

$id = (int)$_GET['id'];

// Mevcut veriyi çek
try {
    $stmt = $db->prepare("SELECT * FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
    $galeri = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$galeri) {
        header("Location: galeri_liste.php");
        exit;
    }
} catch (PDOException $e) {
    die("Veritabanı Hatası: " . $e->getMessage());
}

// Form gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tur = $_POST['tur'];
    $baslik = trim($_POST['baslik'] ?? '');
    $url = $galeri['url']; // Varsayılan olarak eski URL
    
    if ($tur == 'resim') {
        // Yeni dosya yüklendiyse
        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === UPLOAD_ERR_OK) {
            $dosya = $_FILES['dosya'];
            $izinliUzantilar = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $uzanti = strtolower(pathinfo($dosya['name'], PATHINFO_EXTENSION));
            
            if (!in_array($uzanti, $izinliUzantilar)) {
                $hata = 'Sadece resim dosyaları yüklenebilir!';
            } elseif ($dosya['size'] > 5000000) {
                $hata = 'Dosya boyutu 5MB\'dan küçük olmalıdır!';
            } else {
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                $yeniAd = uniqid() . '_' . time() . '.' . $uzanti;
                $hedef = 'uploads/' . $yeniAd;
                
                if (move_uploaded_file($dosya['tmp_name'], $hedef)) {
                    // Eski dosyayı sil
                    if (file_exists($galeri['url'])) {
                        unlink($galeri['url']);
                    }
                    $url = $hedef;
                } else {
                    $hata = 'Dosya yüklenemedi!';
                }
            }
        }
    } elseif ($tur == 'video') {
        $url = trim($_POST['video_url']);
        if (empty($url)) {
            $hata = 'Video URL boş olamaz!';
        }
    }
    
    // Hata yoksa güncelle
    if (!$hata) {
        try {
            $stmt = $db->prepare("UPDATE galeri SET tur = ?, url = ?, baslik = ? WHERE id = ?");
            $stmt->execute([$tur, $url, $baslik, $id]);
            
            header("Location: galeri_liste.php?durum=guncellendi");
            exit;
        } catch (PDOException $e) {
            $hata = 'Veritabanı hatası: ' . $e->getMessage();
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
    <title>Galeri Düzenle - Otopedia Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .info-box i {
            color: #ffc107;
            margin-right: 8px;
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
        
        .current-preview {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .current-preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .upload-area {
            border: 2px dashed #e0e0e0;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .upload-area:hover {
            border-color: var(--primary-color);
            background: #f8f9fa;
        }
        
        .upload-area i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: block;
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
        
        .btn-back {
            background: transparent;
            border: 2px solid #6c757d;
            color: #6c757d;
        }
        
        .btn-back:hover {
            background: #6c757d;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="mb-3">
        <a href="galeri_liste.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Galeri Listesine Dön
        </a>
    </div>
    
    <div class="page-card">
        
        <div class="page-header">
            <i class="fas fa-edit icon"></i>
            <h2>Galeri Öğesini Düzenle</h2>
        </div>
        
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>Not:</strong> Yeni dosya yüklemezseniz mevcut dosya korunacaktır.
        </div>
        
        <?php if($hata): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($hata) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data" id="galeriForm">
            
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-list"></i> İçerik Türü <span class="text-danger">*</span>
                </label>
                <select name="tur" class="form-select" id="turSec" required>
                    <option value="resim" <?= $galeri['tur'] == 'resim' ? 'selected' : '' ?>>📷 Resim</option>
                    <option value="video" <?= $galeri['tur'] == 'video' ? 'selected' : '' ?>>🎥 Video</option>
                </select>
            </div>
            
            <!-- Resim Alanı -->
            <div class="mb-3" id="resimAlan" style="<?= $galeri['tur'] == 'video' ? 'display:none;' : '' ?>">
                <label class="form-label">
                    <i class="fas fa-image"></i> Mevcut Resim
                </label>
                <div class="current-preview">
                    <img src="<?= htmlspecialchars($galeri['url']) ?>" 
                         alt="Mevcut" 
                         onerror="this.src='https://via.placeholder.com/400x300?text=Resim+Bulunamadi'">
                </div>
                
                <label class="form-label">
                    <i class="fas fa-upload"></i> Yeni Resim Yükle (Opsiyonel)
                </label>
                <div class="upload-area" onclick="document.getElementById('resimInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p class="mb-0"><strong>Dosya Seç</strong> veya buraya sürükle</p>
                    <small class="text-muted">Yeni dosya yüklemezseniz mevcut resim korunur</small>
                </div>
                <input type="file" name="dosya" class="form-control d-none" id="resimInput" accept="image/*">
            </div>
            
            <!-- Video Alanı -->
            <div class="mb-3" id="videoAlan" style="<?= $galeri['tur'] == 'resim' ? 'display:none;' : '' ?>">
                <label class="form-label">
                    <i class="fas fa-link"></i> Video URL <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="video_url" 
                       class="form-control"
                       value="<?= $galeri['tur'] == 'video' ? htmlspecialchars($galeri['url']) : '' ?>"
                       placeholder="https://www.youtube.com/embed/VIDEO_ID">
            </div>
            
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-heading"></i> Başlık / Açıklama
                </label>
                <input type="text" 
                       name="baslik" 
                       class="form-control"
                       value="<?= htmlspecialchars($galeri['baslik']) ?>"
                       placeholder="Galeri öğesi için açıklama">
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Değişiklikleri Kaydet
                </button>
                <a href="galeri_liste.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> İptal
                </a>
            </div>
            
        </form>
        
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const turSec = document.getElementById('turSec');
const resimAlan = document.getElementById('resimAlan');
const videoAlan = document.getElementById('videoAlan');

turSec.addEventListener('change', function() {
    if (this.value === 'resim') {
        resimAlan.style.display = 'block';
        videoAlan.style.display = 'none';
    } else {
        resimAlan.style.display = 'none';
        videoAlan.style.display = 'block';
    }
});

document.getElementById('galeriForm').addEventListener('submit', function(e) {
    if (!confirm('Değişiklikleri kaydetmek istediğinizden emin misiniz?')) {
        e.preventDefault();
    }
});
</script>

</body>
</html>