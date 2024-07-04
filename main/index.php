<?php
session_start();

if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
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
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
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
                <a href="#">Artists</span>
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
            <div class="controls">
                <i class="fas fa-chevron-left"></i>
                <i class="fas fa-chevron-right"></i></div>
        </div>
        <div class="content-wrapper"> <!-- New parent container -->
            <div id="searchResults" class="search-results"></div> <!-- Search results container -->
            <div class="recommended">
                <div class="title">
                    <p>Recommended</p>
                    <div class="direction">
                        <i class="fas fa-chevron-left fa-xl"></i>
                        <i class="fas fa-chevron-right fa-xl"></i>
                    </div>
                </div>
                <!-- Sample recommended content -->
                <div class="recommended-content">
                    <p></p>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <div class="controller">
        <div class="player-left">
            <img src="images/music.png" alt="Currently Playing" id="trackImage">
            <div class="track-info">
                <div class="track-title" id="trackTitle">Track <i class="fas fa-heart"></i> <i class="fas fa-heart-broken hide"></i> <i class="fas fa-ellipsis-h"></i></div>
                <div class="track-artist" id="trackArtist">Artist</div>
                <div class="track-album" id="trackAlbum">Album</div>
            </div>
        </div>
        <div class="player-center">
            <button class="player-btn" id="shuffleButton"><i class="fas fa-random"></i></button>
            <button class="player-btn" id="previousButton"><i class="fas fa-step-backward"></i></button>
            <button class="player-btn" id="playPauseButton"><i class="fas fa-play"></i></button>
            <button class="player-btn" id="nextButton"><i class="fas fa-step-forward"></i></button>
            <button class="player-btn" id="repeatButton"><i class="fas fa-redo"></i></button>
            <div class="track-duration">
                <span id="current-time">0:00</span> / <span id="total-time">0:00</span>
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
        let filteredTrackList = []; // To store filtered tracks

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
            resultsDiv.classList.add('show'); // Show search results
            console.log('Tracks rendered:', tracks);

            // Add event listeners to play buttons
            document.querySelectorAll('.play-button').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    playTrack(index, tracks); // Pass the filtered tracks to playTrack
                });
            });
        }

        // Play a selected track
        function playTrack(index, trackList) {
            currentTrackIndex = index;  // Update the current track index

            if (currentAudio) {
                currentAudio.pause();
            }
            const track = trackList[index];
            currentAudio = new Audio(track.preview_url);
            currentAudio.play();
            currentAudio.addEventListener('timeupdate', updateSeekbar);
            currentAudio.addEventListener('ended', nextTrack);

            document.getElementById('trackImage').src = track.image_url;
            document.getElementById('trackTitle').innerText = track.name;
            document.getElementById('trackArtist').innerText = track.artist;
            document.getElementById('trackAlbum').innerText = track.album;

            document.getElementById('playPauseButton').innerHTML = '<i class="fas fa-pause"></i>';
        }

        // Pause or resume the current track
        document.getElementById('playPauseButton').addEventListener('click', function() {
            if (currentAudio) {
                if (currentAudio.paused) {
                    currentAudio.play();
                    this.innerHTML = '<i class="fas fa-pause"></i>';
                } else {
                    currentAudio.pause();
                    this.innerHTML = '<i class="fas fa-play"></i>';
                }
            }
        });

        // Update seekbar as the track plays
        function updateSeekbar() {
            if (currentAudio) {
                const currentTime = currentAudio.currentTime;
                const duration = currentAudio.duration;
                const seekbar = document.getElementById('seekbar');
                seekbar.value = (currentTime / duration) * 100;
                document.getElementById('current-time').innerText = formatTime(currentTime);
                document.getElementById('total-time').innerText = formatTime(duration);
            }
        }

        // Format time in mm:ss format
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
        }

        // Play the next track
        document.getElementById('nextButton').addEventListener('click', nextTrack);

        function nextTrack() {
            currentTrackIndex = (currentTrackIndex + 1) % currentTrackList.length;
            playTrack(currentTrackIndex, currentTrackList);
        }

        // Play the previous track
        document.getElementById('previousButton').addEventListener('click', function() {
            currentTrackIndex = (currentTrackIndex - 1 + currentTrackList.length) % currentTrackList.length;
            playTrack(currentTrackIndex, currentTrackList);
        });

        // Handle volume change
        document.getElementById('volumeSeekbar').addEventListener('input', function() {
            if (currentAudio) {
                currentAudio.volume = this.value / 100;
            }
        });

        // Perform search on input change
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filteredTrackList = currentTrackList.filter(track =>
                track.name.toLowerCase().includes(searchTerm) ||
                track.artist.toLowerCase().includes(searchTerm) ||
                track.album.toLowerCase().includes(searchTerm)
            );
            renderTracks(filteredTrackList);
        });

        // Load tracks initially
        loadTracks();
    </script>
</body>
</html>
