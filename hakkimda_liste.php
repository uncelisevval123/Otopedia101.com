<?php
include 'db.php';

// Hakkımızda verisini çek (tek satır olduğu varsayımıyla)
$query = $db->query("SELECT * FROM hakkimda ORDER BY id DESC LIMIT 1");
$hakkimda = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hakkımda Liste</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Hakkımda Bilgileri</h2>
    <?php if($hakkimda): ?>
    <table class="table table-bordered">
        <tr>
            <th>Hero Başlık</th>
            <td><?= htmlspecialchars($hakkimda['hero_baslik']) ?></td>
        </tr>
        <tr>
            <th>Hero Açıklama</th>
            <td><?= htmlspecialchars($hakkimda['hero_aciklama']) ?></td>
        </tr>
        <tr>
            <th>Biz Kimiz Başlık</th>
            <td><?= htmlspecialchars($hakkimda['biz_kimiz_baslik']) ?></td>
        </tr>
        <tr>
            <th>Biz Kimiz</th>
            <td><?= htmlspecialchars($hakkimda['biz_kimiz']) ?></td>
        </tr>
        <tr>
            <th>Misyon Başlık</th>
            <td><?= htmlspecialchars($hakkimda['misyon_baslik']) ?></td>
        </tr>
        <tr>
            <th>Misyon</th>
            <td><?= htmlspecialchars($hakkimda['misyon']) ?></td>
        </tr>
        <tr>
            <th>Vizyon Başlık</th>
            <td><?= htmlspecialchars($hakkimda['vizyon_baslik']) ?></td>
        </tr>
        <tr>
            <th>Vizyon</th>
            <td><?= htmlspecialchars($hakkimda['vizyon']) ?></td>
        </tr>
        <tr>
            <th>Neden Başlık</th>
            <td><?= htmlspecialchars($hakkimda['neden_baslik']) ?></td>
        </tr>
        <tr>
            <th>Neden List</th>
            <td>
                <?php 
                $nedenler = explode("\n", $hakkimda['neden_list']); 
                echo '<ul>';
                foreach($nedenler as $n) echo '<li>'.htmlspecialchars($n).'</li>';
                echo '</ul>';
                ?>
            </td>
        </tr>
    </table>
    <a href="hakkimda_duzenle.php?id=<?= $hakkimda['id'] ?>" class="btn btn-primary">Düzenle</a>
    <a href="adminpanel.php" class="btn btn-secondary">Geri Dön</a>
    <?php else: ?>
        <p>Henüz Hakkımda bilgisi eklenmemiş.</p>
    <?php endif; ?>

  
</div>
</body>
</html>
