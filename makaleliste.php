<?php
/**
 * ============================================
 * ADMIN MAKALE LİSTESİ (BAĞIMSIZ SAYFA)
 * ============================================
 */

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

try {
    $query = $db->query("SELECT * FROM makaleler ORDER BY id DESC");
    $makaleler = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı Hatası: " . $e->getMessage());
}

$mesaj = '';
if (isset($_GET['durum'])) {
    if ($_GET['durum'] == 'silindi') {
        $mesaj = '<div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> Makale başarıyla silindi!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    } elseif ($_GET['durum'] == 'guncellendi') {
        $mesaj = '<div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> Makale başarıyla güncellendi!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    } elseif ($_GET['durum'] == 'eklendi') {
        $mesaj = '<div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> Makale başarıyla eklendi!
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
    <title>Makale Yönetimi - Otopedia Admin</title>
    
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
            color: white;
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
        
        .btn-edit {
            background: #ffc107;
            color: #333;
            padding: 0.4rem 0.8rem;
        }
        
        .btn-edit:hover {
            background: #ffb300;
            transform: scale(1.05);
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 0.4rem 0.8rem;
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
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f0f0f0;
        }
        
        .preview-img {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .badge {
            padding: 0.5rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-tag {
            background: linear-gradient(135deg, #667eea, #764ba2);
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
        
        @media (max-width: 768px) {
            .page-header h2 {
                font-size: 1.3rem;
            }
            
            .table {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- GERİ DÖN BUTONU -->
    <div class="mb-3">
        <a href="adminpanel.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Admin Panele Dön
        </a>
    </div>
    
    <!-- ANA KART -->
    <div class="page-card">
        
        <!-- SAYFA BAŞLIĞI -->
        <div class="page-header">
            <h2>
                <i class="fas fa-newspaper icon"></i>
                Makale Yönetimi
            </h2>
            <a href="makale_ekle.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Makale
            </a>
        </div>
        
        <?= $mesaj ?>
        
        <?php if (count($makaleler) > 0): ?>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>FOTO</th>
                        <th>BAŞLIK</th>
                        <th>ETİKET</th>
                        <th>İŞLEM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($makaleler as $m): ?>
                    <tr>
                        <td><strong><?= $m['id'] ?></strong></td>
                        <td>
                            <img src="<?= htmlspecialchars($m['kapak_resmi']) ?>" 
                                 alt="Kapak" 
                                 class="preview-img"
                                 onerror="this.src='https://via.placeholder.com/80x50?text=Resim+Yok'">
                        </td>
                        <td><?= htmlspecialchars($m['baslik']) ?></td>
                        <td>
                            <?php if(!empty($m['etiket'])): ?>
                                <span class="badge badge-tag">
                                    <?= htmlspecialchars($m['etiket']) ?>
                                </span>
                            <?php else: ?>
                                <em class="text-muted">-</em>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="makale_duzenle.php?id=<?= $m['id'] ?>" 
                                   class="btn btn-edit btn-sm">
                                    Düzenle
                                </a>
                                <a href="makale_sil.php?id=<?= $m['id'] ?>" 
                                   class="btn btn-delete btn-sm"
                                   onclick="return confirm('Bu makaleyi silmek istediğinizden emin misiniz?')">
                                    Sil
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
                Toplam <strong><?= count($makaleler) ?></strong> makale.
            </small>
        </div>
        
        <?php else: ?>
        
        <div class="empty-state">
            <i class="fas fa-newspaper"></i>
            <h4>Henüz makale yok</h4>
            <p class="text-muted mb-3">İlk makalenizi ekleyerek başlayın</p>
            <a href="makale_ekle.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> İlk Makaleyi Ekle
            </a>
        </div>
        
        <?php endif; ?>
        
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>