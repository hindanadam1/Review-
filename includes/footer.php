<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
<style>
.pied-de-page {
  background-color: #111;
  color: white;
  padding: 40px 50px 20px;
  margin-top: 50px;
  border-top: 2px solid #900;
}

.conteneur-footer {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.section-footer {
  flex: 1;
  min-width: 200px;
  margin-bottom: 20px;
}

.logo-footer {
  color: #ff2222;
  font-size: 24px;
}

.section-footer h3 {
  color: #ff2222;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
}

.section-footer p {
  color: #ccc;
  line-height: 1.6;
}

.section-footer ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.section-footer ul li {
  margin-bottom: 8px;
}

.section-footer ul li a {
  font-size: 14px;
  color: #ccc;
}

.section-footer ul li a:hover {
  color: #ff2222;
}

.icones-reseaux {
  display: flex;
  gap: 15px;
  font-size: 1.2rem;
}

.icones-reseaux i {
  cursor: pointer;
  color: #ccc;
  transition: color 0.2s ease, transform 0.2s ease;
}

.icones-reseaux i:hover {
  color: #ff2222;
  transform: translateY(-2px);
}

.bas-footer {
  text-align: center;
  border-top: 1px solid #333;
  margin-top: 20px;
  padding-top: 10px;
  color: #ccc;
}

.form-newsletter {
  display: flex;
  flex-direction: column;
}

.form-newsletter input {
  padding: 10px 12px;
  margin-bottom: 10px;
  border: none;
  border-radius: 5px;
  outline: none;
  background: #1f1f1f;
  color: #fff;
}

.form-newsletter button {
  padding: 10px;
  border: none;
  border-radius: 5px;
  background-color: #ff2222;
  color: white;
  cursor: pointer;
  transition: 0.3s;
  font-weight: 700;
}

.form-newsletter button:hover {
  background-color: #cc0000;
}

@media (max-width: 768px) {
  .pied-de-page {
    padding: 32px 20px 18px;
  }

  .conteneur-footer {
    flex-direction: column;
  }
}
</style>

<footer class="pied-de-page">
  <div class="conteneur-footer">
    <div class="section-footer">
      <h2 class="logo-footer">Revieweo</h2>
      <p>Partage tes avis sur tes films preferes.</p>
    </div>

    <div class="section-footer">
      <h3>Email</h3>
      <p>contact@revieweo.com</p>
    </div>

    <div class="section-footer">
      <h3>Reseaux sociaux</h3>
      <div class="icones-reseaux">
        <i class="fab fa-facebook"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-instagram"></i>
      </div>
    </div>
  </div>

  <div class="bas-footer">
    <p>&copy; 2026 Revieweo - Tous droits reserves</p>
  </div>
</footer>
