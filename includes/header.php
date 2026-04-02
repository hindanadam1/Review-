<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

$currentUser = $_SESSION['user'] ?? null;
$isLoggedIn = isset($currentUser['id']);
$profileLabel = $isLoggedIn ? htmlspecialchars($currentUser['pseudo'], ENT_QUOTES, 'UTF-8') : 'Profile';
$siteCategories = $categoryService->getAll();

$categoryAnchors = [
    'drama' => 'drama',
    'comedy' => 'comedy',
    'action' => 'action',
    'horror' => 'horror',
    'sci-fi' => 'sci-fi',
    'sci fi' => 'sci-fi',
    'western' => 'western',
    'romance' => 'romance',
    'thriller' => 'thriller',
    'fantasy' => 'fantacy',
    'fantacy' => 'fantacy',
    'apocalypse' => 'apocalypse',
    'martial arts' => 'martial-arts',
    'sports' => 'sports',
];
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
  color: #ff2222;
}

.menu-container {
  flex: 6;
  display: flex;
  justify-content: flex-end;
}

.menu-list {
  display: flex;
  list-style: none;
  align-items: center;
  margin-left: auto;
}

.menu-list-item {
  margin-right: 30px;
  cursor: pointer;
}

.menu-list-item:last-child {
  margin-right: 0;
}

.join-us-link {
  color: #fff !important;
  font-weight: 700;
}

.join-us-link:hover {
  color: #ff2222 !important;
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
  width: 190px;
  max-height: 320px;
  overflow-y: auto;
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
  background-color: #a30f0b;
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
  background: linear-gradient(135deg, #e53935 0%, #a30f0b 100%);
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
      <h1 class="logo">Revieweo</h1>
    </div>
    <div class="menu-container">
      <ul class="menu-list">
        <li class="menu-list-item category-menu">
          <button type="button" class="category-toggle">Categorie</button>
          <div class="category-box" id="categoryBox">
            <ul>
              <?php if (!empty($siteCategories)): ?>
                <?php foreach ($siteCategories as $siteCategory): ?>
                  <?php
                  $categoryName = strtolower(trim((string) $siteCategory['nom']));
                  $anchor = $categoryAnchors[$categoryName] ?? null;
                  ?>
                  <li>
                    <a href="<?= $anchor ? '/revieweo/pages/index.php#' . $anchor : '/revieweo/pages/index.php' ?>">
                      <?= htmlspecialchars($siteCategory['nom'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li><span>Aucune categorie</span></li>
              <?php endif; ?>
            </ul>
          </div>
        </li>
        <li class="menu-list-item">
          <a href="/revieweo/pages/dashboard.php">Dashboard</a>
        </li>
        <li class="menu-list-item">
          <a class="join-us-link" href="/revieweo/auth/register.php">Rejoindre nous</a>
        </li>
      </ul>
    </div>
    <div class="profile-container">
      <div class="profile-text-container">
        <span class="profile-text"><?= $profileLabel ?></span>
        <i class="fas fa-caret-down"></i>
      </div>
    </div>

    <div class="login-box" id="loginBox">
      <?php if ($isLoggedIn): ?>
        <a class="login-box-link dashboard-link" href="/revieweo/auth/login.php">Se connecter</a>
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

