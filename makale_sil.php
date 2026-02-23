<?php
/**
 * ============================================
 * OTOPEDIA MAKALE SİL
 * ============================================
 * Makale silme işlemi
 */

session_start();

// Yetkisiz erişimi engelle
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

// ID kontrolü
$id = $_GET["id"] ?? 0;

if (!$id) {
    header("Location: makaleliste.php?error=no_id");
    exit;
}

try {
    // Önce makalenin var olup olmadığını kontrol et
    $kontrol = $db->prepare("SELECT id, baslik FROM makaleler WHERE id=?");
    $kontrol->execute([$id]);
    $makale = $kontrol->fetch(PDO::FETCH_ASSOC);
    
    if (!$makale) {
        header("Location: makaleliste.php?error=not_found");
        exit;
    }
    
    // Makaleyi sil
    $sil = $db->prepare("DELETE FROM makaleler WHERE id=?");
    $sil->execute([$id]);
    
    // Başarılı silme
    header("Location: makaleliste.php?success=deleted");
    exit;
    
} catch (PDOException $e) {
    // Hata durumunda
    header("Location: makaleliste.php?error=delete_failed");
    exit;
}