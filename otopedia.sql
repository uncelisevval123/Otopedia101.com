-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 23 Şub 2026, 23:22:33
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `otopedia`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`id`, `kullanici_adi`, `sifre`, `olusturma_tarihi`) VALUES
(2, 'admin1', '$2y$10$x80.dhtYMQsxLcv6j54CMOp/tMGUcdouqiPHAfP8QYFPPaPMxN.cu', '2026-02-18 15:04:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `tur` enum('resim','video') NOT NULL DEFAULT 'resim',
  `url` varchar(500) NOT NULL,
  `baslik` varchar(255) DEFAULT NULL,
  `olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `galeri`
--

INSERT INTO `galeri` (`id`, `tur`, `url`, `baslik`, `olusturma_tarihi`) VALUES
(1, 'resim', 'img/blog/blog1.jpg', 'Galeri 1', '2026-02-18 15:31:59'),
(2, 'resim', 'img/blog/blog7.jpg', 'Galeri 2', '2026-02-18 15:31:59'),
(3, 'resim', 'img/blog/blog3.jpg', 'Galeri 3', '2026-02-18 15:31:59'),
(4, 'resim', 'img/blog/blog4.jpg', 'Galeri 4', '2026-02-18 15:31:59'),
(8, 'resim', 'uploads/699cd2becb894_1771885246.jpg', '500x', '2026-02-23 22:20:46');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hakkimda`
--

CREATE TABLE `hakkimda` (
  `id` int(11) NOT NULL,
  `hero_baslik` varchar(200) NOT NULL DEFAULT 'Otopedia101 Hakkında',
  `hero_aciklama` text DEFAULT NULL,
  `biz_kimiz_baslik` varchar(200) NOT NULL DEFAULT 'Biz Kimiz?',
  `biz_kimiz` text DEFAULT NULL,
  `misyon_baslik` varchar(200) NOT NULL DEFAULT 'Misyonumuz',
  `misyon` text DEFAULT NULL,
  `vizyon_baslik` varchar(200) NOT NULL DEFAULT 'Vizyonumuz',
  `vizyon` text DEFAULT NULL,
  `neden_baslik` varchar(200) NOT NULL DEFAULT 'Neden Otopedia?',
  `neden_list` text DEFAULT NULL COMMENT 'Her madde ayrı satırda',
  `guncelleme_tarihi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `hakkimda`
--

INSERT INTO `hakkimda` (`id`, `hero_baslik`, `hero_aciklama`, `biz_kimiz_baslik`, `biz_kimiz`, `misyon_baslik`, `misyon`, `vizyon_baslik`, `vizyon`, `neden_baslik`, `neden_list`, `guncelleme_tarihi`) VALUES
(1, 'Otopedia101 Hakkında', 'Otomobil dünyasına tarafsız, sade ve gerçek içerikler.', 'Biz Kimiz?', 'Otopedia; otomobil incelemeleri, kullanıcı deneyimleri ve teknik içerikleri sade bir dille sunmak amacıyla kurulmuş bağımsız bir otomotiv platformudur.', 'Misyonumuz', 'Otomobil almayı düşünen herkese doğru, tarafsız ve anlaşılır bilgiler sunmak.', 'Vizyonumuz', 'Türkiye\'nin en güvenilir dijital otomobil rehberi olmak.', 'Neden Otopedia?', 'Gerçek kullanıcı odaklı içerikler\r\nAbartısız, sade anlatım\r\nVideo + makale destekli incelemeler\r\nReklam değil deneyim', '2026-02-18 19:53:18');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `makaleler`
--

CREATE TABLE `makaleler` (
  `id` int(11) NOT NULL,
  `baslik` varchar(255) DEFAULT NULL,
  `alt_baslik` varchar(255) DEFAULT NULL,
  `ozet` text DEFAULT NULL,
  `icerik` text DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `resim_url` text DEFAULT NULL,
  `sayfa` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `makaleler`
--

INSERT INTO `makaleler` (`id`, `baslik`, `alt_baslik`, `ozet`, `icerik`, `kategori`, `resim_url`, `sayfa`) VALUES
(1, 'BMW M4 Competition', 'Saf performans ve agresif M karakteri', 'Saf performans, agresif tasarım ve M ruhu.', 'BMW M4 Competition, yüksek performanslı coupe segmentinde saf sürüş keyfi arayanlar için geliştirilmiş bir model. 510 beygir gücündeki motoru, sert süspansiyon yapısı ve arka itiş karakteriyle sürücüsüne tam kontrol sunuyor. 0–100 km/s hızlanmasını 3.9 saniyede tamamlayan M4, pist odaklı yapısını günlük kullanımla dengeliyor.', 'Test Sürüşü', 'https://ddztmb1ahc6o7.cloudfront.net/policarobmw/wp-content/uploads/2020/12/05112156/P90399203_highRes_the-new-bmw-m4-compe.jpg', 'makale1.php'),
(2, 'Ferrari Tasarım Felsefesi', 'Estetik ve mühendisliğin kusursuz birleşimi', 'Ferrari yi Ferrari yapan tasarım çizgileri.', 'Ferrari, sadece bir otomobil markası değil, aynı zamanda bir tasarım ikonudur. Her çizgi aerodinamik bir amaca hizmet ederken, markanın DNA sını da yansıtır. Ferrari tasarımlarında güzellik, performansın doğal bir sonucudur.', 'Süper Spor', 'https://ferraris-online.com/wp-content/uploads/2024/03/cover-photo-2.jpg', 'makale2.php'),
(3, 'Yeni Nesil Hibrit Motorlar', 'Performans ve verimliliğin kesişimi', 'Yeni nesil hibrit motor teknolojileri.', 'Hibrit motorlar artık sadece ekonomi değil, performans da sunuyor. Elektrik ve içten yanmalı motorun birlikte çalışması, sürüş karakterini tamamen değiştiriyor.', 'Teknoloji', 'https://www.ototasarruf.com/uploads/photos/onemlibilgiler/hibrit-hybrid-nedir.webp', 'makale3.php'),
(4, 'Sedan mı SUV mu?', 'Hangisi senin için daha mantıklı?', 'Sedan ve SUV modellerini karşılaştırdık.', 'Sedan ve SUV modeller, farklı kullanıcı ihtiyaçlarına hitap eder. Konfor, sürüş pozisyonu ve kullanım amacı, tercihleri doğrudan etkiler.', 'Karşılaştırma', 'https://blog2.araba.com/wp-content/uploads/2025/01/suv-vs-sedann.jpg', 'makale4.php');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kullanici_adi` (`kullanici_adi`),
  ADD KEY `idx_kullanici_adi` (`kullanici_adi`);

--
-- Tablo için indeksler `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tur` (`tur`),
  ADD KEY `idx_olusturma_tarihi` (`olusturma_tarihi`);

--
-- Tablo için indeksler `hakkimda`
--
ALTER TABLE `hakkimda`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `makaleler`
--
ALTER TABLE `makaleler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `hakkimda`
--
ALTER TABLE `hakkimda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `makaleler`
--
ALTER TABLE `makaleler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
