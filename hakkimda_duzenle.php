<?php
include 'db.php';

$id = $_GET['id'] ?? 0;

$query = $db->prepare("SELECT * FROM hakkimda WHERE id = ?");
$query->execute([$id]);
$hakkimda = $query->fetch(PDO::FETCH_ASSOC);

$hata = '';
$basarili = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hero_baslik = $_POST['hero_baslik'];
    $hero_aciklama = $_POST['hero_aciklama'];
    $biz_kimiz_baslik = $_POST['biz_kimiz_baslik'];
    $biz_kimiz = $_POST['biz_kimiz'];
    $misyon_baslik = $_POST['misyon_baslik'];
    $misyon = $_POST['misyon'];
    $vizyon_baslik = $_POST['vizyon_baslik'];
    $vizyon = $_POST['vizyon'];
    $neden_baslik = $_POST['neden_baslik'];
    $neden_list = $_POST['neden_list'];

    $stmt = $db->prepare("UPDATE hakkimda SET 
        hero_baslik=?, hero_aciklama=?, 
        biz_kimiz_baslik=?, biz_kimiz=?,
        misyon_baslik=?, misyon=?,
        vizyon_baslik=?, vizyon=?,
        neden_baslik=?, neden_list=?
        WHERE id=?");

    if($stmt->execute([
        $hero_baslik, $hero_aciklama,
        $biz_kimiz_baslik, $biz_kimiz,
        $misyon_baslik, $misyon,
        $vizyon_baslik, $vizyon,
        $neden_baslik, $neden_list,
        $id
    ])) {
        $basarili = "Bilgiler başarıyla güncellendi!";
        $query = $db->prepare("SELECT * FROM hakkimda WHERE id = ?");
        $query->execute([$id]);
        $hakkimda = $query->fetch(PDO::FETCH_ASSOC);
    } else {
        $hata = "Güncelleme sırasında hata oluştu!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hakkımda Düzenle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Hakkımda Düzenle</h2>

    <?php if($hata): ?>
        <div class="alert alert-danger"><?= $hata ?></div>
    <?php elseif($basarili): ?>
        <div class="alert alert-success"><?= $basarili ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Hero Başlık</label>
            <input type="text" name="hero_baslik" class="form-control" value="<?= htmlspecialchars($hakkimda['hero_baslik']) ?>">
        </div>
        <div class="mb-3">
            <label>Hero Açıklama</label>
            <textarea name="hero_aciklama" class="form-control"><?= htmlspecialchars($hakkimda['hero_aciklama']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Biz Kimiz Başlık</label>
            <input type="text" name="biz_kimiz_baslik" class="form-control" value="<?= htmlspecialchars($hakkimda['biz_kimiz_baslik']) ?>">
        </div>
        <div class="mb-3">
            <label>Biz Kimiz</label>
            <textarea name="biz_kimiz" class="form-control"><?= htmlspecialchars($hakkimda['biz_kimiz']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Misyon Başlık</label>
            <input type="text" name="misyon_baslik" class="form-control" value="<?= htmlspecialchars($hakkimda['misyon_baslik']) ?>">
        </div>
        <div class="mb-3">
            <label>Misyon</label>
            <textarea name="misyon" class="form-control"><?= htmlspecialchars($hakkimda['misyon']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Vizyon Başlık</label>
            <input type="text" name="vizyon_baslik" class="form-control" value="<?= htmlspecialchars($hakkimda['vizyon_baslik']) ?>">
        </div>
        <div class="mb-3">
            <label>Vizyon</label>
            <textarea name="vizyon" class="form-control"><?= htmlspecialchars($hakkimda['vizyon']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Neden Başlık</label>
            <input type="text" name="neden_baslik" class="form-control" value="<?= htmlspecialchars($hakkimda['neden_baslik']) ?>">
        </div>
        <div class="mb-3">
            <label>Neden Listesi (her maddeyi alt satıra yaz)</label>
            <textarea name="neden_list" class="form-control" rows="5"><?= htmlspecialchars($hakkimda['neden_list']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="hakkimda_liste.php" class="btn btn-secondary">Geri Dön</a>
    </form>
</div>
</body>
</html>
