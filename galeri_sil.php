<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}
require "db.php";

$id = intval($_GET["id"]);
if ($id) {
    // Önce resim dosyasını da silelim
    $sorgu = $db->prepare("SELECT url, tur FROM galeri WHERE id=?");
    $sorgu->execute([$id]);
    $galeri = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($galeri && $galeri['tur'] == 'resim' && file_exists($galeri['url'])) {
        unlink($galeri['url']);
    }

    $db->prepare("DELETE FROM galeri WHERE id=?")->execute([$id]);
}

header("Location: galeri_liste.php?durum=silindi");
exit;