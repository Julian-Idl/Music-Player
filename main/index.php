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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>     
<div class="sidebar" >
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
<div class="body"s>
    <div class="header">
        <div class="navigation">
            <i class="fas fa-arrow-left fa-xl"></i>
            <i class="fas fa-arrow-right fa-xl"></i>
        </div>
        <div class="search">
            <input type="text" placeholder="Search">
        </div>
        <div class="controls">
            <i class="fas fa-chevron-left"></i>
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
    <div class="recomended">
        <div class="title">
            <p>Recomended</p>
            <div class="direction">
                <i class=" fas fa-chevron-left fa-xl"></i>
                <i class="fas fa-chevron-right fa-xl"></i>
            </div>
        </div>
        <div class="front">
            <img src="./images/3.jpg" alt="">
            <a href="#">New Arrivals</a>
            <a href="#">Newest music
            </a>
            
        </div>
        <div class="front">
            <img src="./images/graduation.jpg" alt="">
            <a href="#">Graduation</a>
            <a href="#">Kanye West</a>
            
        </div>
        <div class="front">
            <img src="./images/euphoria.jpg" alt="">
            <a href="#">Euphoria</a>
            <a href="#">Kendrick Lamar</a>
            
        </div>
        <div class="front">
            <img src="./images/heroes.jpg" alt="">
            <a href="#">Heroes</a>
            <a href="#">Metro Boomin</a>
            
        </div>
    </div>
    <div class="playlist">
        <div class="title">
            <p>Your Favourites</p>
            <div class="direction">
                <i class="fa-solid fa-chevron-left fa-xl"></i>
                <i class="fa-solid fa-chevron-right fa-xl"></i>
            </div>
        </div>
        <div class="mid">
            <img src="./images/insano.jpg" alt="">
            <a href="#">Insano </a>
            <a href="#">Kid Cudi</a>
        </div>
        <div class="mid">
            <img src="./images/yeezus.jpg" alt="">
            <a href="#">Yeezus</a>
            <a href="#">Kanye West</a>
        </div>
        <div class="mid">
            <img src="./images/22.jpg" alt="">
            <a href="#">22</a>
            <a href="#">Bon Iver</a>
        </div>
        <div class="mid">
            <img src="./images/currents.jpg" alt="">
            <a href="#">Currents</a>
            <a href="#">Tame Impala</a>
        </div>
        <div class="mid">
            <img src="./images/after hours.jpg" alt="">
            <a href="1.html">After Hours</a>
            <a href="#">The Weeknd</a>
        </div>
    </div>
    <div class="playlist">
        <div class="title">
            <p>New Releases</p>
            <div class="direction">
                <i class="fas fa-chevron-left fa-xl"></i>
                <i class="fas fa-chevron-right fa-xl"></i>
            </div>
        </div>
        <div class="mid">
            <img src="./images/darktimes.jpg" alt="">
            <a href="#">Dark Times</a>
            <a href="#">Vince Staples</a>
        </div>
        <div class="mid">
            <img src="./images/wsdty.jpg" alt="">
            <a href="#">Dont Trust You</a>
            <a href="#">Future</a>
        </div>
        <div class="mid">
            <img src="./images/spiderman.jpg" alt="">
            <a href="#">Spiderman</a>
            <a href="#">Metro Boomin</a>
        </div>
        <div class="mid">
            <img src="./images/skins.jpg" alt="">
            <a href="#">Skins</a>
            <a href="#">XXXTentacion</a>
        </div>
        <div class="mid">
            <img src="./images/illmatic.jpg" alt="">
            <a href="#">Illmatic</a>
            <a href="#">Nas</a>
        </div>
    </div>
</div>
<div class="controller">
        <div class="player-left">
            <img src="images\after hours.jpg" alt="Currently Playing">
            <div class="track-info">
                <div class="track-title">Faith <i class="fas fa-heart"></i> <i class="fas fa-heart-broken hide"></i> <i class="fas fa-ellipsis-h"></i></div>
                <div class="track-artist">The Weeknd</div>
                <div class="track-album">After Hours</div>
            </div>
        </div>
        <div class="player-center">
            <button class="player-btn"><i class="fas fa-random"></i></button>
            <button class="player-btn"><i class="fas fa-step-backward"></i></button>
            <button class="player-btn"><i class="fas fa-play"></i></button>
            <button class="player-btn"><i class="fas fa-step-forward"></i></button>
            <button class="player-btn"><i class="fas fa-redo"></i></button>
            <div class="track-duration">
                <span id="current-time">0:00</span> / <span id="total-time">4:05</span>
            </div>
        </div>
        <div class="player-right">
            <button class="player-btn"><i class="fas fa-volume-up"></i></button>
            <button class="player-btn"><i class="fas fa-list"></i></button>
        </div>
    </div>
</body>
</html>