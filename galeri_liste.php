<?php
/**
 * ============================================
 * OTOPEDIA GALERİ LİSTESİ
 * ============================================
 * Galeri öğelerini listeleme sayfası
 */

session_start();

// Yetkisiz erişimi engelle
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

// Galeri verilerini çek
try {
    $query = $db->query("SELECT * FROM galeri ORDER BY id DESC");
    $galeriler = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı Hatası: " . $e->getMessage());
}

// Silme işlemi için mesaj kontrolü
$mesaj = '';
if (isset($_GET['durum'])) {
    if ($_GET['durum'] == 'silindi') {
        $mesaj = '<div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> Galeri öğesi başarıyla silindi!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    } elseif ($_GET['durum'] == 'guncellendi') {
        $mesaj = '<div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> Galeri öğesi başarıyla güncellendi!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Galeri Listesi - Otopedia Admin</title>
    
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
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-header h2 {
            margin: 0;
            color: #333;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-header .icon {
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
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
            transform: translateY(-2px);
        }
        
        .btn-edit {
            background: #ffc107;
            color: #333;
        }
        
        .btn-edit:hover {
            background: #ffb300;
            transform: scale(1.05);
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
            transform: scale(1.05);
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .table {
            margin: 0;
            background: white;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .table thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f0f0f0;
        }
        
        .preview-img {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .preview-img:hover {
            transform: scale(1.5);
            z-index: 10;
            position: relative;
        }
        
        .badge {
            padding: 0.5rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-resim {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .badge-video {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-state i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        .empty-state h4 {
            color: #999;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-header h2 {
                font-size: 1.3rem;
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
            
            .preview-img {
                width: 60px;
                height: 40px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- Geri Dön Butonu -->
    <div class="mb-3">
        <a href="adminpanel.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> admin panele Dön
        </a>
    </div>
    
    <!-- Ana Kart -->
    <div class="page-card">
        
        <!-- Sayfa Başlığı -->
        <div class="page-header">
            <h2>
                <i class="fas fa-images icon"></i>
                Galeri Yönetimi
            </h2>
            <a href="galeri_ekle.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Ekle
            </a>
        </div>
        
        <!-- Mesaj Gösterimi -->
        <?= $mesaj ?>
        
        <!-- Tablo veya Boş Durum -->
        <?php if (count($galeriler) > 0): ?>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 120px;">TÜR</th>
                        <th style="width: 140px;">ÖNİZLEME</th>
                        <th>BAŞLIK</th>
                        <th style="width: 180px;" class="text-center">İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($galeriler as $g): ?>
                    <tr>
                        <td><strong>#<?= $g['id'] ?></strong></td>
                        <td>
                            <?php if($g['tur'] == 'resim'): ?>
                                <span class="badge badge-resim">
                                    <i class="fas fa-image"></i> Resim
                                </span>
                            <?php else: ?>
                                <span class="badge badge-video">
                                    <i class="fas fa-video"></i> Video
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($g['tur'] == 'resim'): ?>
                                <img src="<?= htmlspecialchars($g['url']) ?>" 
                                     alt="Galeri" 
                                     class="preview-img"
                                     onerror="this.src='https://via.placeholder.com/100x60?text=Resim+Yok'">
                            <?php else: ?>
                                <i class="fas fa-play-circle" style="font-size: 2rem; color: var(--primary-color);"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $baslik = !empty($g['baslik']) ? htmlspecialchars($g['baslik']) : '<em class="text-muted">Başlık yok</em>';
                            echo $baslik;
                            ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="galeri_duzenle.php?id=<?= $g['id'] ?>" 
                                   class="btn btn-edit btn-sm"
                                   title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="galeri_sil.php?id=<?= $g['id'] ?>" 
                                   class="btn btn-delete btn-sm"
                                   onclick="return confirm('Bu galeri öğesini silmek istediğinizden emin misiniz?')"
                                   title="Sil">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-3 text-muted">
            <small>
                <i class="fas fa-info-circle"></i>
                Toplam <strong><?= count($galeriler) ?></strong> galeri öğesi bulunuyor.
            </small>
        </div>
        
        <?php else: ?>
        
        <!-- Boş Durum -->
        <div class="empty-state">
            <i class="fas fa-images"></i>
            <h4>Henüz galeri öğesi yok</h4>
            <p class="text-muted mb-3">İlk galeri öğenizi ekleyerek başlayın</p>
            <a href="galeri_ekle.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> İlk Galeri Öğesini Ekle
            </a>
        </div>
        
        <?php endif; ?>
        
    </div>
    
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Resim önizleme hover efekti
document.querySelectorAll('.preview-img').forEach(img => {
    img.addEventListener('mouseenter', function() {
        this.style.cursor = 'pointer';
    });
});

// Silme onayı
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Bu galeri öğesini kalıcı olarak silmek istediğinizden emin misiniz?\n\nBu işlem geri alınamaz!')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>

</body>
</html>