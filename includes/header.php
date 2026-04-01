<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? null;
$isLoggedIn = isset($currentUser['id']);
$profileLabel = $isLoggedIn ? htmlspecialchars($currentUser['pseudo'], ENT_QUOTES, 'UTF-8') : 'Profile';
?>
<style>
* {
  margin: 0;
}

html {
  scroll-behavior: smooth;
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
  z-index: 9999;
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
  cursor: pointer;
}

.category-menu {
  position: relative;
}

.category-toggle {
  background: transparent;
  border: none;
  color: white;
  font: inherit;
  cursor: pointer;
  padding: 0;
}

.category-box {
  position: absolute;
  top: 40px;
  left: 0;
  background: #222;
  padding: 10px;
  border-radius: 10px;
  display: none;
  width: 150px;
}

.category-box ul {
  list-style: none;
  padding: 0;
}

.category-box li {
  padding: 8px;
  cursor: pointer;
}

.category-box li:hover {
  background-color: #4dbf00;
}

.category-box.active {
  display: block;
}

.menu-list-item a {
  text-decoration: none;
  color: white;
}

.menu-list-item.active {
  font-weight: bold;
}

.profile-container {
  flex: 2;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  cursor: pointer;
}

.profile-text-container {
  margin: 0 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
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
  min-width: 180px;
}

.login-box.active {
  display: block;
}

.login-box-link,
.login-btn,
.register-btn,
.logout-btn {
  display: block;
  width: 100%;
  margin: 5px 0;
  padding: 8px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: white;
  text-align: center;
  text-decoration: none;
  box-sizing: border-box;
}

.login-btn,
.login-box-link.dashboard-link {
  background-color: #4dbf00;
}

.register-btn {
  background-color: #555;
}

.logout-btn {
  background-color: #9b1c1c;
}
</style>

<div class="navbar">
  <div class="navbar-container">
    <div class="logo-container">
      <h1 class="logo">logo</h1>
    </div>
    <div class="menu-container">
      <ul class="menu-list">
        <li class="menu-list-item category-menu">
          <button type="button" class="category-toggle">Categorie</button>
          <div class="category-box" id="categoryBox">
            <ul>
              <li><a href="/revieweo/pages/index.php#drama">Drama</a></li>
              <li><a href="/revieweo/pages/index.php#comedy">Comedy</a></li>
              <li><a href="/revieweo/pages/index.php#action">Action</a></li>
              <li><a href="/revieweo/pages/index.php#horror">Horror</a></li>
              <li><a href="/revieweo/pages/index.php#sci-fi">Sci-fi</a></li>
              <li><a href="/revieweo/pages/index.php#western">Western</a></li>
              <li><a href="/revieweo/pages/index.php#romance">Romance</a></li>
              <li><a href="/revieweo/pages/index.php#thriller">Thriller</a></li>
              <li><a href="/revieweo/pages/index.php#fantacy">Fantasy</a></li>
              <li><a href="/revieweo/pages/index.php#apocalypse">Apocalypse</a></li>
              <li><a href="/revieweo/pages/index.php#martial-arts">Martial Arts</a></li>
              <li><a href="/revieweo/pages/index.php#sports">Sports</a></li>
            </ul>
          </div>
        </li>
        <li class="menu-list-item">
          <a href="/revieweo/pages/dashboard.php">Dashboard</a>
        </li>
        <li class="menu-list-item">
          <a href="/revieweo/pages/create_review.php">Write review</a>
        </li>
      </ul>
    </div>
    <div class="profile-container">
      <img class="profile-picture" src="" alt="Photo de profil" />
      <div class="profile-text-container">
        <span class="profile-text"><?= $profileLabel ?></span>
        <i class="fas fa-caret-down"></i>
      </div>
    </div>

    <div class="login-box" id="loginBox">
      <?php if ($isLoggedIn): ?>
        <a class="login-box-link dashboard-link" href="/revieweo/pages/dashboard.php">Mon dashboard</a>
        <a class="logout-btn" href="/revieweo/auth/logout.php">Deconnexion</a>
      <?php else: ?>
        <button class="login-btn" type="button" onclick="window.location.href='/revieweo/auth/login.php'">Login</button>
        <button class="register-btn" type="button" onclick="window.location.href='/revieweo/auth/register.php'">Register</button>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
const profileTextContainer = document.querySelector(".profile-text-container");
const loginBox = document.getElementById("loginBox");
const categoryMenu = document.querySelector(".category-menu");
const categoryBox = document.getElementById("categoryBox");
const categoryToggle = document.querySelector(".category-toggle");
const categoryLinks = document.querySelectorAll(".category-box a");

if (categoryToggle && categoryBox) {
  categoryToggle.addEventListener("click", (event) => {
    event.stopPropagation();
    categoryBox.classList.toggle("active");
  });
}

if (profileTextContainer && loginBox) {
  profileTextContainer.addEventListener("click", (event) => {
    event.stopPropagation();
    loginBox.classList.toggle("active");
  });
}

categoryLinks.forEach((link) => {
  link.addEventListener("click", () => {
    categoryBox.classList.remove("active");
  });
});

document.addEventListener("click", (event) => {
  if (categoryMenu && !categoryMenu.contains(event.target)) {
    categoryBox.classList.remove("active");
  }

  if (profileTextContainer && loginBox && !profileTextContainer.contains(event.target) && !loginBox.contains(event.target)) {
    loginBox.classList.remove("active");
  }
});
</script>
