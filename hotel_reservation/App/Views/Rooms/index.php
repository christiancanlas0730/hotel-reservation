<h1 class="mb-4"><i class="fas fa-door-open"></i> Manage Rooms</h1>

<div class="rooms-container">

    <?php foreach($data['rooms'] as $room): ?>
    <?php
        $roomFolder = $_SERVER['DOCUMENT_ROOT'] . '/hotel_reservation/public/assets/rooms/' . $room['id'];
        $images = [];
        if(is_dir($roomFolder)){
            $files = scandir($roomFolder);
            foreach($files as $file){
                if(in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp'])){
                    $images[] = $file;
                }
            }
        }
    ?>
    <div class="room-card">
        <div class="room-images">
            <?php if(!empty($images)): ?>
                <?php foreach($images as $img): ?>
                    <img src="/hotel_reservation/public/assets/rooms/<?= $room['id'] ?>/<?= htmlspecialchars($img) ?>" 
                         alt="Room Image" class="room-img" onclick="openLightboxRoom(this)">
                <?php endforeach; ?>
            <?php else: ?>
                <span class="no-image">No image</span>
            <?php endif; ?>
        </div>
        <div class="room-info">
            <h3>Room <?= $room['room_number'] ?> - <?= $room['type'] ?></h3>
            <p>Price: <?= $room['price'] ?></p>
            <p>Status: <strong><?= $room['status'] ?></strong></p>
            <div class="room-actions">
                <a href="/hotel_reservation/public/rooms/edit/<?= $room['id'] ?>" class="btn btn-primary">Edit</a>
                <a href="/hotel_reservation/public/rooms/delete/<?= $room['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this room?')">Delete</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <a href="/hotel_reservation/public/dashboard" class="btn btn-secondary mt-4">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- LIGHTBOX -->
<div id="lightbox" class="lightbox-overlay">
    <span id="prev" onclick="prevImage()">&#10094;</span>
    <img id="lightbox-img" src="" alt="Room Image">
    <span id="next" onclick="nextImage()">&#10095;</span>
    <span id="close" onclick="closeLightbox()">&times;</span>
</div>

<style>
/* === PAGE BACKGROUND === */
body {
    background: #f0f4f8;
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
}

/* === CONTAINER === */
.rooms-container {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    justify-content: center;
    margin-bottom: 50px;
}

/* === ROOM CARD === */
.room-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
    width: 320px;
    padding: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.room-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(212, 175, 55, 0.4);
}

/* === ROOM IMAGES === */
.room-images {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 15px;
}

.room-img {
    width: 100px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}

.room-img:hover {
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.6);
}

.no-image {
    color: #999;
    font-size: 0.9em;
}

/* === ROOM INFO === */
.room-info h3 {
    margin: 0 0 8px;
    font-size: 1.2rem;
    color: #333;
}

.room-info p {
    margin: 4px 0;
    color: #555;
}

/* === ROOM ACTIONS === */
.room-actions {
    margin-top: 10px;
    display: flex;
    gap: 10px;
}

/* === LIGHTBOX === */
.lightbox-overlay {
    display: none;
    position: fixed;
    z-index: 9999;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.lightbox-overlay img {
    max-width: 80%;
    max-height: 80%;
    border-radius: 8px;
    margin-bottom: 20px;
}

.lightbox-overlay #close {
    position: absolute;
    top: 20px; right: 30px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}

.lightbox-overlay #prev, .lightbox-overlay #next {
    position: absolute;
    top: 50%;
    font-size: 50px;
    color: #fff;
    cursor: pointer;
    user-select: none;
    transform: translateY(-50%);
    padding: 0 15px;
}

.lightbox-overlay #prev { left: 20px; }
.lightbox-overlay #next { right: 20px; }
</style>

<script>
// 🔹 LIGHTBOX (combined all images)
let allImages = Array.from(document.querySelectorAll("img.room-img")).map(img => img.src);
let currentIndex = 0;

function openLightboxRoom(imgElement) {
    currentIndex = allImages.indexOf(imgElement.src);
    document.getElementById('lightbox-img').src = allImages[currentIndex];
    document.getElementById('lightbox').style.display = 'flex';
}

function nextImage() {
    currentIndex = (currentIndex + 1) % allImages.length;
    document.getElementById('lightbox-img').src = allImages[currentIndex];
}

function prevImage() {
    currentIndex = (currentIndex - 1 + allImages.length) % allImages.length;
    document.getElementById('lightbox-img').src = allImages[currentIndex];
}

function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
}
</script>
