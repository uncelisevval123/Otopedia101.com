<?php
session_start();


if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    header("Location: adminpanel.php");
    exit;
}

require "db.php";

$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $kullanici_adi = trim($_POST["kullanici_adi"] ?? "");
    $sifre         = $_POST["sifre"] ?? "";

    if ($kullanici_adi === "" || $sifre === "") {
        $hata = "Kullanıcı adı ve şifre boş olamaz!";
    } else {
        try {
         
            $sorgu = $db->prepare("SELECT * FROM admin WHERE kullanici_adi = ?");
            $sorgu->execute([$kullanici_adi]);
            $admin = $sorgu->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($sifre, $admin["sifre"])) {
                $_SESSION["admin"] = true;
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_kullanici"] = $admin["kullanici_adi"];

                header("Location: adminpanel.php");
                exit;
            } else {
                $hata = "Kullanıcı adı veya şifre yanlış!";
            }

        } catch (PDOException $e) {
            $hata = "Bir hata oluştu: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Giriş</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.login-box {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}
</style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="login-box p-5" style="width:400px">
    <h3 class="text-center mb-4">🔐 Admin Girişi</h3>

    <?php if (!empty($hata)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($hata) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Kullanıcı Adı</label>
            <input type="text" name="kullanici_adi" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Şifre</label>
            <input type="password" name="sifre" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Giriş Yap</button>
        <a href="index.php" class="btn btn-outline-secondary w-100 py-2 mt-2">← Anasayfaya Dön</a>
    </form>

   
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>