<?php
// index.php généré automatiquement en fusionnant index.html, style.css et app.js
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Design</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <style>
* {
  margin: 0;
}

body {
  font-family: "Roboto", sans-serif;
}

.navbar {
  width: 100%;
  height: 50px;
  background-color: black;
  position: sticky;
  top: 0;
}

.navbar-container {
  display: flex;
  align-items: center;
  padding: 0 50px;
  height: 100%;
  color: white;
  font-family: "Sen", sans-serif;
}

.logo-container {
  flex: 1;
}

.logo {
  font-size: 30px;
  color: #4dbf00;
}

.menu-container {
  flex: 6;
}

.menu-list {
  display: flex;
  list-style: none;
}

.menu-list-item {
  margin-right: 30px;
}

.menu-list-item.active {
  font-weight: bold;
}
.profile-container {
  flex: 2;
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

.profile-text-container {
  margin: 0 20px;
}

.profile-picture {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
}

.login-box {
  position: absolute;
  top: 60px;
  right: 50px;
  background: #222;
  padding: 15px;
  border-radius: 10px;
  display: none;
}

.login-box.active {
  display: block;
}

.login-btn, .register-btn {
  display: block;
  width: 100%;
  margin: 5px 0;
  padding: 8px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: white;
}

.login-btn {
  background-color: #4dbf00;
}

.register-btn {
  background-color: #555;
}

.container {
  background-color: #151515;
  min-height: calc(100vh - 50px);
  color: white;
}

.content-container {
  margin-left: 50px;
}

.featured-content {
  background: linear-gradient(to bottom, rgba(0, 0, 0, 0), #151515),
              url('img/f-1.jpg');
  height: 50vh;
  padding: 50px;
}

.featured-title {
  width: 200px;
}

.featured-desc {
  width: 500px;
  color: lightgray;
  margin: 30px 0;
}

.featured-button {
  background-color: #4dbf00;
  color: white;
  padding: 10px 20px;
  border-radius: 10px;
  border: none;
  outline: none;
  font-weight: bold;
}

.movie-list-container {
  padding: 0 20px;
}

.movie-list-wrapper {
  position: relative;
  overflow: hidden;
}

.movie-list {
  display: flex;
  align-items: center;
  height: 300px;
  transform: translateX(0);
  transition: all 1s ease-in-out;
}

.movie-list-item {
  margin-right: 30px;
  position: relative;
}

.movie-list-item:hover .movie-list-item-img {
  transform: scale(1.2);
  margin: 0 30px;
  opacity: 0.5;
}

.movie-list-item:hover .movie-list-item-title,
.movie-list-item:hover .movie-list-item-desc,
.movie-list-item:hover .movie-list-item-button {
  opacity: 1;
}

.movie-list-item-img {
  transition: all 1s ease-in-out;
  width: 270px;
  height: 200px;
  object-fit: cover;
  border-radius: 20px;
}

.movie-list-item-title {
  background-color: #333;
  padding: 0 10px;
  font-size: 32px;
  font-weight: bold;
  position: absolute;
  top: 10%;
  left: 50px;
  opacity: 0;
  transition: 1s all ease-in-out;
}

.movie-list-item-desc {
  background-color: #333;
  padding: 10px;
  font-size: 14px;
  position: absolute;
  top: 30%;
  left: 50px;
  width: 230px;
  opacity: 0;
  transition: 1s all ease-in-out;
}

.movie-list-item-button {
  padding: 10px;
  background-color: #4dbf00;
  color: white;
  border-radius: 10px;
  outline: none;
  border: none;
  cursor: pointer;
  position: absolute;
  bottom: 20px;
  left: 50px;
  opacity: 0;
  transition: 1s all ease-in-out;
}

.arrow {
  font-size: 120px;
  position: absolute;
  top: 90px;
  right: 0;
  color: lightgray;
  opacity: 0.5;
  cursor: pointer;
}

.container.active {
  background-color: white;
}

.movie-list-title.active {
  color: black;
}

.navbar-container.active {
  background-color: white;

  color: black;
}

.left-menu-icon.active{
    color: black;
}

.toggle.active{
    background-color: black;
}

.toggle-ball.active{
    background-color: white;
    transform: translateX(-20px);
}

@media only screen and (max-width: 940px){
    .menu-container{
        display: none;
    }
}
    </style>
</head>
<body>
    <!-- navbar -->
    <div class="navbar">
      <div class="navbar-container">
        <div class="logo-container">
          <h1 class="logo">logo</h1>
        </div>
        <div class="menu-container">
          <ul class="menu-list">
            <li class="menu-list-item active">Cátegorie</li>
            <li class="menu-list-item">Dashboard</li>
            <li class="menu-list-item">Write review </li>

          </ul>
        </div>
        <div class="profile-container">
          <img class="profile-picture" src="" alt="" />
          <div class="profile-text-container">
            <span class="profile-text">Profile</span>
            <i class="fas fa-caret-down"></i>
          </div>
        </div>
        
        <div class="login-box" id="loginBox">
          <button class="login-btn" onclick="window.location.href='login.php'">Login</button>
          <button class="register-btn" onclick="window.location.href='register.php'">Register</button>
        </div>

      </div>
    </div>

    <div class="container">
      <!-- main movie -->
      <div class="content-container">
        <div class="featured-content">
          <img class="featured-title" src="img/f-t-1.png" alt="" />
          <p class="featured-desc">
            ttttttttttttttttttttttttttttttttttttttt
            ttttttttttttttttttttttttttttttttttttttttttttttt
            ttttttttttttttttttttttttttttttttttttttttttttttttttttttt
            ttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt
            tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt
            ttttttttttttttttttttttttttt
          </p>
          <button class="featured-button">WATCH</button>
        </div>
        <!-- list movie 1 -->
        <div class="movie-list-container">
          <h1 class="movie-list-title">NEW RELEASES</h1>
          <div class="movie-list-wrapper">
            <div class="movie-list">
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/1.jpeg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/2.jpeg" alt="" />
                <span class="movie-list-item-title">Star Wars</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/3.jpg" alt="" />
                <span class="movie-list-item-title">Storm</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/4.jpg" alt="" />
                <span class="movie-list-item-title">1917</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/5.jpg" alt="" />
                <span class="movie-list-item-title">Avengers</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/6.jpg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/7.jpg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
            </div>
            <i class="fas fa-chevron-right arrow"></i>
          </div>
        </div>

        <!-- list movie 2 -->
        <div class="movie-list-container">
          <h1 class="movie-list-title">NEW RELEASES</h1>
          <div class="movie-list-wrapper">
            <div class="movie-list">
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/1.jpeg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/2.jpeg" alt="" />
                <span class="movie-list-item-title">Star Wars</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/3.jpg" alt="" />
                <span class="movie-list-item-title">Storm</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/4.jpg" alt="" />
                <span class="movie-list-item-title">1917</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/5.jpg" alt="" />
                <span class="movie-list-item-title">Avengers</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/6.jpg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/7.jpg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
            </div>
            <i class="fas fa-chevron-right arrow"></i>
          </div>
        </div>

        <!-- list movie 3 -->
        <div class="movie-list-container">
          <h1 class="movie-list-title">NEW RELEASES</h1>
          <div class="movie-list-wrapper">
            <div class="movie-list">
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/1.jpeg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/2.jpeg" alt="" />
                <span class="movie-list-item-title">Star Wars</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/3.jpg" alt="" />
                <span class="movie-list-item-title">Storm</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/4.jpg" alt="" />
                <span class="movie-list-item-title">1917</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/5.jpg" alt="" />
                <span class="movie-list-item-title">Avengers</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/6.jpg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
              <div class="movie-list-item">
                <img class="movie-list-item-img" src="img/7.jpg" alt="" />
                <span class="movie-list-item-title">Her</span>
                <p class="movie-list-item-desc">
                  description description description
                </p>
                <button class="movie-list-item-button">Watch</button>
              </div>
            </div>
            <i class="fas fa-chevron-right arrow"></i>
                </div>
            </div>
        </div>
    </div>
    <script>
const arrows = document.querySelectorAll(".arrow");
const movieLists = document.querySelectorAll(".movie-list");

const profileText = document.querySelector(".profile-text");
const loginBox = document.getElementById("loginBox");


arrows.forEach((arrow, i) => {
  const itemNumber = movieLists[i].querySelectorAll("img").length;
  let clickCounter = 0;
  arrow.addEventListener("click", () => {
    const ratio = Math.floor(window.innerWidth / 270);
    clickCounter++;
    if (itemNumber - (4 + clickCounter) + (4 - ratio) >= 0) {
      movieLists[i].style.transform = `translateX(${movieLists[i].computedStyleMap().get("transform")[0].x.value - 300}px)`;
    } else {
      movieLists[i].style.transform = "translateX(0)";
      clickCounter = 0;
    }
  });

  console.log(Math.floor(window.innerWidth / 270));
});

profileText.addEventListener("click", () => {
  loginBox.classList.toggle("active");
});


    </script>
</body>
</html>
