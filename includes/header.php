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

</style>

<!-- navbar -->
<div class="navbar">
      <div class="navbar-container">
        <div class="logo-container">
          <h1 class="logo">logo</h1>
        </div>
        <div class="menu-container">
          <ul class="menu-list">
            <li class="menu-list-item category-menu">
                Cátegorie
                <div class="category-box" id="categoryBox">
                    <ul>
                    <li><a href="/Review-/pages/index.php#drama">Drama</a></li>
                    <li><a href="/Review-/pages/index.php#comedy">Comedy</a></li>
                    <li><a href="/Review-/pages/index.php#action">Action</a></li>
                    <li><a href="/Review-/pages/index.php#horror">Horror</a></li>
                    <li><a href="/Review-/pages/index.php#sci-fi">Sci-fi</a></li>
                    <li><a href="/Review-/pages/index.php#western">Western</a></li>
                    <li><a href="/Review-/pages/index.php#romance">Romance</a></li>
                    <li><a href="/Review-/pages/index.php#thriller">Thriller</a></li>
                    <li><a href="/Review-/pages/index.php#fantasy">Fantasy</a></li>
                    <li><a href="/Review-/pages/index.php#apocalypse">Apocalypse</a></li>
                    <li><a href="/Review-/pages/index.php#martial-art">Martial Arts</a></li>
                    <li><a href="/Review-/pages/index.php#sports">Sports</a></li>
                    </ul>
                </div>
            </li>
            <li class="menu-list-item">
                <a href="/Review-/pages/dashboard.php">Dashboard</a>
            </li>            
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
          <button class="login-btn" onclick="window.location.href='/Review-/Auth/login.php'">Login</button>
          <button class="register-btn" onclick="window.location.href='/Review-/Auth/register.php'">Register</button>
        </div>

      </div>
    </div>


    <script>


const profileTextContainer = document.querySelector(".profile-text-container");
const loginBox = document.getElementById("loginBox");

const categoryMenu = document.querySelector(".category-menu");
const categoryBox = document.getElementById("categoryBox");

categoryMenu.addEventListener("click", () => {
  categoryBox.classList.toggle("active");
});

profileTextContainer.addEventListener("click", () => {
  loginBox.classList.toggle("active");
});

    </script>