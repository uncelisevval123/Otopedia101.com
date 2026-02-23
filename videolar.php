
<?php
/* ================= YOUTUBE API ================= */
$apiKey = "AIzaSyCz4tRrUXVs4AN18ilO1toCOpf0V7tG6Yw";
$channelId = "UChfY_S-Q7a_0NttbuLrfNEQ";
$maxResults = 6;

/* SADECE VIDEO ÇEK */
$url = "https://www.googleapis.com/youtube/v3/search?" . http_build_query([
    'key'        => $apiKey,
    'channelId'  => $channelId,
    'part'       => 'snippet',
    'order'      => 'date',
    'maxResults' => $maxResults,
    'type'       => 'video'
]);

$data = json_decode(file_get_contents($url), true);
$videolar = $data['items'] ?? [];
?>
<?php include "header.php"; ?>
<style>
body{margin:0;background:#0e0e0e;color:#fff;font-family:Arial}
.navbar-area{background:#000;padding:15px 30px}
.nav-container{display:flex;justify-content:space-between;align-items:center}
.logo a{color:#ff2c2c;font-size:22px;font-weight:bold;text-decoration:none}
.nav-menu ul{list-style:none;display:flex;gap:20px;margin:0}
.nav-menu a{color:#fff;text-decoration:none}
.nav-menu a.active{color:#ff2c2c}

.hero-video{height:70vh;background-size:cover;background-position:center;position:relative;cursor:pointer}
.hero-overlay{position:absolute;inset:0;background:rgba(0,0,0,.55);display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center}
.hero-overlay h1{font-size:36px;margin-bottom:20px}
.hero-play{background:#ff2c2c;border:none;color:#fff;padding:14px 28px;border-radius:50px;font-size:18px}

.slider-title{margin:40px 30px 20px}
.video-slider{display:flex;gap:20px;padding:0 30px 40px;overflow-x:auto}
.slide{min-width:280px;height:160px;background-size:cover;background-position:center;border-radius:12px;cursor:pointer;position:relative}
.slide span{position:absolute;bottom:0;width:100%;padding:10px;background:rgba(0,0,0,.7);font-size:14px}

.video-modal{position:fixed;inset:0;background:rgba(0,0,0,.8);display:none;justify-content:center;align-items:center;z-index:999}
.video-modal.active{display:flex}
.video-modal-content{width:90%;max-width:900px;position:relative}
.video-modal iframe{width:100%;height:500px}
.video-close{position:absolute;top:-40px;right:0;font-size:30px;cursor:pointer}
</style>
</head>

<body>


<?php if (!empty($videolar)): 
$hero = $videolar[0]; ?>

<!-- HERO -->
<div class="hero-video"
     data-video="<?= $hero['id']['videoId']; ?>"
     style="background-image:url('https://img.youtube.com/vi/<?= $hero['id']['videoId']; ?>/maxresdefault.jpg')">
  <div class="hero-overlay">
    <h1><?= htmlspecialchars($hero['snippet']['title']); ?></h1>
    <button class="hero-play">
      <i class="fa-solid fa-play"></i> Videoyu İzle
    </button>
  </div>
</div>

<!-- SLIDER -->
<h3 class="slider-title">Son İncelemeler</h3>
<div class="video-slider">
<?php foreach (array_slice($videolar, 1) as $video): ?>
  <div class="slide"
       data-video="<?= $video['id']['videoId']; ?>"
       style="background-image:url('<?= $video['snippet']['thumbnails']['high']['url']; ?>')">
    <span><?= htmlspecialchars($video['snippet']['title']); ?></span>
  </div>
<?php endforeach; ?>
</div>

<?php endif; ?>

<!-- MODAL -->
<div class="video-modal">
  <div class="video-modal-content">
    <span class="video-close">&times;</span>
    <iframe src="" allowfullscreen></iframe>
  </div>
</div>



<?php include "footer.php"; ?>

<script>
const modal = document.querySelector(".video-modal");
const iframe = modal.querySelector("iframe");

document.querySelectorAll("[data-video]").forEach(el=>{
  el.onclick=()=>openVideo(el.dataset.video);
});

function openVideo(id){
  iframe.src="https://www.youtube.com/embed/"+id+"?autoplay=1&rel=0";
  modal.classList.add("active");
}
document.querySelector(".video-close").onclick=closeVideo;
modal.onclick=e=>{if(e.target===modal)closeVideo();}
function closeVideo(){modal.classList.remove("active");iframe.src="";}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
