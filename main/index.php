<?php
session_start();

if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    // Load users from the file
    $users = json_decode(file_get_contents('users.json'), true);
    
    $username = $_COOKIE['username'];
    if (isset($users[$username])) {
        $_SESSION['username'] = $username;
    }
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<div class="sidebar">
    <div class="profile">
        <div class="userphoto"><i class="fa-solid fa-circle-user fa-3x shape"></i></div>
        <div class="bars"><i class="fa-solid fa-bars fa-2x shape hide"></i>
        <i class="fa-solid fa-bars-staggered fa-2x shape"></i></div>
    </div>
    <div class="menu">
        <a href="#"> Home</a>
        <a href="#"> Explore</a>
        <a href="#"> Library</a>
    </div>
    <div class="collection">
        <p>MY COLLECTION</p>
        <span>
            <i class="fa-solid fa-headphones fa-xl"></i>
            <a href="#">Radio Mix</a>
        </span>
        <span>
            <i class="fa-solid fa-list-ul fa-xl"></i>
            <a href="#">Playlists</a>
        </span>
        <span>
            <i class="fa-solid fa-record-vinyl fa-xl"></i>
            <a href="#">Tracks</a>
        </span>
        <span>
            <i class="fa-solid fa-music fa-xl"></i>
            <a href="#">Artists</a>
        </span>
    </div>
    <div class="albums">
        <p>MY ALBUMS</p>
        <a href="1.html">After Hours</a>
        <a href="#">Yeezus</a>
        <a href="#">The Forever Story</a>
        <a href="#">Illmatic</a>
        <a href="#">AstroWorld</a>
    </div>
</div>
<div class="body">
    <div class="header">
        <div class="navigation">
            <i class="fas fa-arrow-left fa-xl"></i>
            <i class="fas fa-arrow-right fa-xl"></i>
        </div>
        <div class="search">
            <input type="text" id="searchInput" placeholder="Search">
            <button id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div id="searchResults" class="search-results"></div>
        <div class="controls">
            <i class="fas fa-chevron-left"></i>
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
    <div class="results"></div>
    <div class="recomended">
        <div class="title">
            <p>Recomended</p>
            <div class="direction">
                <i class=" fas fa-chevron-left fa-xl"></i>
                <i class="fas fa-chevron-right fa-xl"></i>
            </div>
        </div>
        <!-- Existing content goes here -->
    </div>
</div>
<div class="controller">
    <div class="player-left">
        <img src="cover/blinding_lights.jpg" alt="Currently Playing" id="trackImage">
        <div class="track-info">
            <div class="track-title" id="trackTitle">Faith <i class="fas fa-heart"></i> <i class="fas fa-heart-broken hide"></i> <i class="fas fa-ellipsis-h"></i></div>
            <div class="track-artist" id="trackArtist">The Weeknd</div>
            <div class="track-album" id="trackAlbum">After Hours</div>
        </div>
    </div>
    <div class="player-center">
        <button class="player-btn" id="shuffleButton"><i class="fas fa-random"></i></button>
        <button class="player-btn" id="previousButton"><i class="fas fa-step-backward"></i></button>
        <button class="player-btn" id="playPauseButton"><i class="fas fa-play"></i></button>
        <button class="player-btn" id="nextButton"><i class="fas fa-step-forward"></i></button>
        <button class="player-btn" id="repeatButton"><i class="fas fa-redo"></i></button>
        <div class="track-duration">
            <span id="current-time">0:00</span> / <span id="total-time">4:05</span>
        </div>
        <input type="range" id="seekbar" min="0" max="100" value="0">
    </div>
    <div class="player-right">
        <button class="player-btn" id="volumeButton"><i class="fas fa-volume-up"></i></button>
        <input type="range" id="volumeSeekbar" min="0" max="100" value="100">
        <button class="player-btn"><i class="fas fa-list"></i></button>
    </div>
</div>

<script>
let currentAudio = null;
let currentTrackIndex = 0;
let currentTrackList = [];

// Load tracks from JSON file
function loadTracks() {
    fetch('tracks.json')
        .then(response => response.json())
        .then(data => {
            currentTrackList = data;
            console.log('Tracks loaded:', data);
        })
        .catch(error => console.error('Error loading tracks:', error));
}

// Render tracks based on search results
function renderTracks(tracks) {
    const resultsDiv = document.getElementById('searchResults');
    resultsDiv.innerHTML = '';
    tracks.forEach((track, index) => {
        const trackElement = document.createElement('div');
        trackElement.classList.add('track');
        trackElement.innerHTML = `
            <img src="${track.image_url}" alt="${track.name}">
            <div class="track-details">
                <p class="track-name">${track.name}</p>
                <p class="track-artist">${track.artist}</p>
            </div>
            <button class="play-button" data-index="${index}"><i class="fas fa-play"></i></button>
        `;
        resultsDiv.appendChild(trackElement);
    });
    console.log('Tracks rendered:', tracks); // Debugging log
}

// Play a selected track
function playTrack(index) {
    if (currentAudio) {
        currentAudio.pause();
    }
    const track = currentTrackList[index];
    currentAudio = new Audio(track.preview_url);
    currentAudio.play();
    currentAudio.addEventListener('timeupdate', updateSeekbar);
    currentAudio.addEventListener('ended', nextTrack);

    document.getElementById('trackImage').src = track.image_url;
    document.getElementById('trackTitle').innerText = track.name;
    document.getElementById('trackArtist').innerText = track.artist;
    document.getElementById('trackAlbum').innerText = track.album;

    document.getElementById('playPauseButton').innerHTML = '<i class="fas fa-pause"></i>';
    currentTrackIndex = index;
    console.log('Playing track:', track); // Debugging log
}

// Update seekbar based on current audio time
function updateSeekbar() {
    const seekbar = document.getElementById('seekbar');
    const currentTimeSpan = document.getElementById('current-time');
    const totalTimeSpan = document.getElementById('total-time');
    const currentTime = currentAudio.currentTime;
    const duration = currentAudio.duration;

    seekbar.value = (currentTime / duration) * 100;
    currentTimeSpan.innerText = formatTime(currentTime);
    totalTimeSpan.innerText = formatTime(duration);
}

// Format time in minutes and seconds
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

// Toggle play/pause functionality
function togglePlayPause() {
    if (currentAudio) {
        if (currentAudio.paused) {
            currentAudio.play();
            document.getElementById('playPauseButton').innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            currentAudio.pause();
            document.getElementById('playPauseButton').innerHTML = '<i class="fas fa-play"></i>';
        }
    }
}

// Play the next track in the list
function nextTrack() {
    if (currentTrackIndex < currentTrackList.length - 1) {
        playTrack(currentTrackIndex + 1);
    }
}

// Play the previous track in the list
function previousTrack() {
    if (currentTrackIndex > 0) {
        playTrack(currentTrackIndex - 1);
    }
}

// Search tracks based on input query
function searchTracks(query) {
    const filteredTracks = currentTrackList.filter(track =>
        track.name.toLowerCase().includes(query) ||
        track.artist.toLowerCase().includes(query) ||
        track.album.toLowerCase().includes(query) ||
        track.tags.some(tag => tag.toLowerCase().includes(query))
    );
    renderTracks(filteredTracks);
}

// Event listeners for search functionality and playback controls
document.getElementById('searchButton').addEventListener('click', () => {
    const query = document.getElementById('searchInput').value.toLowerCase();
    console.log('Search query:', query); // Debugging log
    searchTracks(query);
});

document.getElementById('searchInput').addEventListener('input', (event) => {
    const query = event.target.value.toLowerCase();
    console.log('Search query (input event):', query); // Debugging log
    searchTracks(query);
});

document.getElementById('searchResults').addEventListener('click', (event) => {
    if (event.target.closest('.play-button')) {
        const index = event.target.closest('.play-button').getAttribute('data-index');
        playTrack(index);
    }
});

document.getElementById('playPauseButton').addEventListener('click', togglePlayPause);
document.getElementById('nextButton').addEventListener('click', nextTrack);
document.getElementById('previousButton').addEventListener('click', previousTrack);
document.getElementById('seekbar').addEventListener('input', (event) => {
    if (currentAudio) {
        currentAudio.currentTime = (event.target.value / 100) * currentAudio.duration;
    }
});
document.getElementById('volumeSeekbar').addEventListener('input', (event) => {
    if (currentAudio) {
        currentAudio.volume = event.target.value / 100;
    }
});

// Load tracks on page load
window.onload = loadTracks;
</script>

</body>
</html>
