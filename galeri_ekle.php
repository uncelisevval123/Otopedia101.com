<?php
/**
 * ============================================
 * OTOPEDIA GALERİ EKLE
 * ============================================
 * Yeni galeri öğesi ekleme sayfası
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tur = $_POST['tur'];
    $baslik = trim($_POST['baslik'] ?? '');
    $url = '';

    if ($tur == 'resim') {
        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === UPLOAD_ERR_OK) {
            $dosya = $_FILES['dosya'];
            $izinliUzantilar = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $uzanti = strtolower(pathinfo($dosya['name'], PATHINFO_EXTENSION));
            
            // Dosya türü kontrolü
            if (!in_array($uzanti, $izinliUzantilar)) {
                $hata = 'Sadece resim dosyaları yüklenebilir! (jpg, jpeg, png, gif, webp)';
            } 
            // Dosya boyutu kontrolü (5MB)
            elseif ($dosya['size'] > 5000000) {
                $hata = 'Dosya boyutu 5MB\'dan küçük olmalıdır!';
            } 
            else {
                // Uploads klasörünü kontrol et
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                // Benzersiz dosya adı oluştur
                $yeniAd = uniqid() . '_' . time() . '.' . $uzanti;
                $hedef = 'uploads/' . $yeniAd;
                
                if (move_uploaded_file($dosya['tmp_name'], $hedef)) {
                    $url = $hedef;
                } else {
                    $hata = 'Dosya yüklenemedi! Lütfen tekrar deneyin.';
                }
            }
        } else {
            $hata = 'Lütfen bir resim dosyası seçin!';
        }
    } 
    elseif ($tur == 'video') {
        $url = trim($_POST['video_url']);
        if (empty($url)) {
            $hata = 'Video URL boş olamaz!';
        }
    } 
    else {
        $hata = 'Geçersiz işlem!';
    }

    // Hata yoksa veritabanına kaydet
    if (!$hata && !empty($url)) {
        try {
            $stmt = $db->prepare("INSERT INTO galeri (tur, url, baslik, olusturma_tarihi) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$tur, $url, $baslik]);
            $basarili = 'Galeri öğesi başarıyla eklendi!';
            
            // Formu temizle
            $_POST = [];
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
    <title>Yeni Galeri Öğesi Ekle - Otopedia Admin</title>
    
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
        
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .info-box i {
            color: var(--primary-color);
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
        
        .upload-area {
            border: 2px dashed #e0e0e0;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 1rem;
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
        
        .preview-container {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            display: none;
        }
        
        #resimPreview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
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
        <a href="galeri_liste.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Galeri Listesine Dön
        </a>
    </div>
    
    <!-- Ana Kart -->
    <div class="page-card">
        
        <!-- Sayfa Başlığı -->
        <div class="page-header">
            <i class="fas fa-plus-circle icon"></i>
            <h2>Yeni Galeri Öğesi Ekle</h2>
        </div>
        
        <!-- Bilgi Kutusu -->
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>Not:</strong> Resim veya video ekleyebilirsiniz. Resimler için dosya yükleyin, videolar için YouTube embed linkini girin.
        </div>
        
        <!-- Mesaj Gösterimi -->
        <?php if($hata): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($hata) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif($basarili): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($basarili) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Form -->
        <form method="post" enctype="multipart/form-data" id="galeriForm">
            
            <!-- Tür Seçimi -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-list"></i> İçerik Türü <span class="text-danger">*</span>
                </label>
                <select name="tur" class="form-select" id="turSec" required>
                    <option value="resim" selected>📷 Resim</option>
                    <option value="video">🎥 Video</option>
                </select>
            </div>
            
            <!-- Resim Yükleme Alanı -->
            <div class="mb-3" id="resimAlan">
                <label class="form-label">
                    <i class="fas fa-image"></i> Resim Dosyası <span class="text-danger">*</span>
                </label>
                <div class="upload-area" onclick="document.getElementById('resimInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p class="mb-0"><strong>Dosya Seç</strong> veya buraya sürükle</p>
                    <small class="text-muted">JPG, PNG, GIF, WEBP (Max: 5MB)</small>
                </div>
                <input type="file" 
                       name="dosya" 
                       class="form-control d-none" 
                       id="resimInput"
                       accept="image/*">
                
                <!-- Önizleme -->
                <div class="preview-container" id="previewContainer">
                    <p class="text-muted mb-2"><small>Önizleme:</small></p>
                    <img id="resimPreview" src="#" alt="Önizleme">
                </div>
            </div>
            
            <!-- Video URL Alanı -->
            <div class="mb-3" id="videoAlan" style="display:none;">
                <label class="form-label">
                    <i class="fas fa-link"></i> Video URL <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="video_url" 
                       class="form-control"
                       placeholder="https://www.youtube.com/embed/VIDEO_ID">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> YouTube embed linkini girin (örn: https://www.youtube.com/embed/dQw4w9WgXcQ)
                </small>
            </div>
            
            <!-- Başlık -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-heading"></i> Başlık / Açıklama
                </label>
                <input type="text" 
                       name="baslik" 
                       class="form-control"
                       placeholder="Galeri öğesi için açıklama (opsiyonel)">
            </div>
            
            <!-- Butonlar -->
            <div class="btn-group-custom">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Galeri Öğesini Kaydet
                </button>
                <a href="galeri_liste.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> İptal
                </a>
            </div>
            
        </form>
        
    </div>
    
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const turSec = document.getElementById('turSec');
const resimAlan = document.getElementById('resimAlan');
const videoAlan = document.getElementById('videoAlan');
const resimInput = document.getElementById('resimInput');
const resimPreview = document.getElementById('resimPreview');
const previewContainer = document.getElementById('previewContainer');
const uploadArea = document.querySelector('.upload-area');

// Tür değiştiğinde alan gösterimi
turSec.addEventListener('change', function() {
    if (this.value === 'resim') {
        resimAlan.style.display = 'block';
        videoAlan.style.display = 'none';
        resimInput.setAttribute('required', 'required');
        document.querySelector('input[name="video_url"]').removeAttribute('required');
    } else {
        resimAlan.style.display = 'none';
        videoAlan.style.display = 'block';
        resimInput.removeAttribute('required');
        document.querySelector('input[name="video_url"]').setAttribute('required', 'required');
    }
});

// Resim önizleme
resimInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        // Dosya boyutu kontrolü
        if (file.size > 5000000) {
            alert('Dosya boyutu 5MB\'dan küçük olmalıdır!');
            this.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        // Dosya türü kontrolü
        const izinliTipler = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!izinliTipler.includes(file.type)) {
            alert('Sadece resim dosyaları yüklenebilir! (JPG, PNG, GIF, WEBP)');
            this.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            resimPreview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Drag & Drop desteği
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = 'var(--primary-color)';
    uploadArea.style.background = '#f8f9fa';
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.style.borderColor = '#e0e0e0';
    uploadArea.style.background = 'white';
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = '#e0e0e0';
    uploadArea.style.background = 'white';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        resimInput.files = files;
        resimInput.dispatchEvent(new Event('change'));
    }
});

// Form validasyonu
document.getElementById('galeriForm').addEventListener('submit', function(e) {
    const tur = turSec.value;
    
    if (tur === 'resim') {
        if (!resimInput.files || resimInput.files.length === 0) {
            e.preventDefault();
            alert('Lütfen bir resim dosyası seçin!');
            return false;
        }
    } else if (tur === 'video') {
        const videoUrl = document.querySelector('input[name="video_url"]').value.trim();
        if (!videoUrl) {
            e.preventDefault();
            alert('Lütfen video URL\'sini girin!');
            return false;
        }
    }
    
    if (!confirm('Galeri öğesini kaydetmek istediğinizden emin misiniz?')) {
        e.preventDefault();
        return false;
    }
});
</script>

</body>
</html>