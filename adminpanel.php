<?php
/**
 * ============================================
 * OTOPEDIA ADMIN PANEL
 * ============================================
 */

session_start();

// ============================================
// CACHE ENGELLEMESİ - Geri tuşuyla erişimi kapat
// ============================================
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

// Yetkisiz erişimi engelle
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("Location: login.php");
    exit;
}

require "db.php";

$adminName = "Admin";
$adminUsername = "";

if (isset($_SESSION["admin_id"])) {
    try {
        $stmt = $db->prepare("SELECT username FROM admins WHERE id = ?");
        $stmt->execute([$_SESSION["admin_id"]]);
        $admin = $stmt->fetch();
        
        if ($admin) {
            $adminUsername = $admin["username"];
            $adminName = ucfirst($admin["username"]);
        }
    } catch (PDOException $e) {
        // Hata durumunda varsayılan değer kullan
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">

    <!-- Cache engelleme meta tagları -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Yönetim Paneli - Otopedia101</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #ff5c5c;
            --secondary-color: #ff7b00;
            --dark-bg: #1a1a1a;
            --card-bg: #ffffff;
            --text-primary: #333333;
            --text-secondary: #666666;
            --shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            box-shadow: var(--shadow);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }
        
        .admin-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }
        
        .admin-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 2rem;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            border: 2px solid transparent;
            height: 100%;
        }
        
        .admin-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-color);
        }
        
        .admin-card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .admin-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .admin-card-desc {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0;
        }
        
        .admin-card-badge {
            display: inline-block;
            padding: 5px 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 1rem;
        }
        
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: white;
            color: var(--primary-color);
            transform: scale(1.05);
        }
        
        @media (max-width: 768px) {
            .page-header h1 { font-size: 1.5rem; }
            .admin-card { padding: 1.5rem; }
            .admin-card-icon { font-size: 2.5rem; }
            .admin-info { display: none; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container">
        <span class="navbar-brand">
            <i class="fas fa-car-side"></i> Otopedia Admin
        </span>
        
        <div class="d-flex align-items-center gap-3">
            <div class="admin-info">
                <div class="admin-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span><?= htmlspecialchars($adminName) ?></span>
            </div>
            
            <a href="logout.php" class="btn btn-logout" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i> Çıkış
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    
    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> Yönetim Paneli</h1>
        <p>Otopedia101 içeriklerini buradan yönetebilirsiniz</p>
    </div>
    
    <div class="row g-4 justify-content-center">
        
        <div class="col-lg-4 col-md-6">
            <a href="makaleliste.php" class="admin-card">
                <div class="admin-card-icon"><i class="fas fa-newspaper"></i></div>
                <h3 class="admin-card-title">Makaleler</h3>
                <p class="admin-card-desc">Blog yazılarını ve içerikleri yönetin</p>
                <span class="admin-card-badge">Yönet</span>
            </a>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <a href="galeri_liste.php" class="admin-card">
                <div class="admin-card-icon"><i class="fas fa-images"></i></div>
                <h3 class="admin-card-title">Galeri</h3>
                <p class="admin-card-desc">Fotoğraf galerisini güncelleyin</p>
                <span class="admin-card-badge">Yönet</span>
            </a>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <a href="hakkimda_liste.php" class="admin-card">
                <div class="admin-card-icon"><i class="fas fa-info-circle"></i></div>
                <h3 class="admin-card-title">Hakkımda</h3>
                <p class="admin-card-desc">Hakkımda sayfasını düzenleyin</p>
                <span class="admin-card-badge">Yönet</span>
            </a>
        </div>
        
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Geri tuşu ile erişimi engelle
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function() {
        history.pushState(null, null, location.href);
    });

    // Kartlara hover animasyonu
    document.querySelectorAll('.admin-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Çıkış butonuna onay
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        if (!confirm('Çıkış yapmak istediğinizden emin misiniz?')) {
            e.preventDefault();
        }
    });
</script>

</body>
</html>