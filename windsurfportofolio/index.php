<?php
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/auth.php';

$data = get_data();
$general = $data['general'];
$about = $data['about'];
$skills = $data['skills'];
$projects = $data['projects'];
$contact = $data['contact'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo sanitize($general['site_name']); ?></title>
  <meta name="description" content="<?php echo sanitize($general['hero_subtitle']); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div id="scrollProgress" aria-hidden="true"></div>
  <header class="site-header">
    <div class="container nav-container">
      <div class="logo">
        <a href="#hero" class="brand"><?php echo sanitize($general['site_name']); ?></a>
      </div>
      <nav class="nav">
        <a href="#about">À propos</a>
        <a href="#skills">Compétences</a>
        <a href="#projects">Projets</a>
        <a href="#contact">Contact</a>
      </nav>
    </div>
  </header>

  <section id="hero" class="hero">
    <canvas id="particle-canvas" aria-hidden="true"></canvas>
    <div class="container hero-inner">
      <h1 class="hero-title gradient-text"><?php echo sanitize($general['hero_title']); ?></h1>
      <p class="hero-subtitle"><?php echo sanitize($general['hero_subtitle']); ?></p>
      <a href="#projects" class="btn btn-primary btn-glow"><?php echo sanitize($general['hero_cta_text']); ?></a>
      <div class="scroll-indicator">
        <span>Faites défiler</span>
        <div class="mouse"><div class="wheel"></div></div>
      </div>
    </div>
  </section>

  <main>
    <section id="about" class="section reveal-on-scroll">
      <div class="container grid-2">
        <div class="about-img-wrap">
          <img src="<?php echo sanitize($about['profile_image']); ?>" alt="Profil" class="about-img">
          <div class="glow-circle"></div>
        </div>
        <div>
          <h2 class="section-title"><?php echo sanitize($about['title']); ?></h2>
          <p class="lead"><?php echo nl2br(sanitize($about['text'])); ?></p>
        </div>
      </div>
    </section>

    <section id="skills" class="section alt reveal-on-scroll">
      <div class="container">
        <h2 class="section-title">Compétences</h2>
        <div class="skills">
          <?php foreach ($skills as $s): ?>
            <div class="skill">
              <div class="skill-header">
                <span class="skill-name"><?php echo sanitize($s['name']); ?></span>
                <span class="skill-level"><?php echo (int) $s['level']; ?>%</span>
              </div>
              <div class="skill-bar" data-level="<?php echo (int) $s['level']; ?>">
                <div class="skill-bar-fill"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="projects" class="section reveal-on-scroll">
      <div class="container">
        <h2 class="section-title">Projets</h2>
        <div class="project-grid">
          <?php foreach ($projects as $p): ?>
            <article class="project-card" data-tilt>
              <div class="project-image">
                <img src="<?php echo sanitize($p['image']); ?>" alt="<?php echo sanitize($p['title']); ?>">
              </div>
              <div class="project-content">
                <h3><?php echo sanitize($p['title']); ?></h3>
                <p><?php echo sanitize($p['description']); ?></p>
                <?php if (!empty($p['tags'])): ?>
                  <div class="tags">
                    <?php foreach ($p['tags'] as $t): ?>
                      <span class="tag"><?php echo sanitize($t); ?></span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
                <?php if (!empty($p['url']) && $p['url'] !== '#'): ?>
                  <a class="btn btn-outline" href="<?php echo sanitize($p['url']); ?>" target="_blank" rel="noopener">Voir</a>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="contact" class="section alt reveal-on-scroll">
      <div class="container grid-3">
        <div>
          <h2 class="section-title">Contact</h2>
          <ul class="contact-list">
            <li><strong>Email:</strong> <a href="mailto:<?php echo sanitize($contact['email']); ?>"><?php echo sanitize($contact['email']); ?></a></li>
            <li><strong>Téléphone:</strong> <a href="tel:<?php echo preg_replace('/\s+/', '', sanitize($contact['phone'])); ?>"><?php echo sanitize($contact['phone']); ?></a></li>
            <li><strong>Localisation:</strong> <?php echo sanitize($contact['location']); ?></li>
          </ul>
        </div>
        <div>
          <h3>Réseaux</h3>
          <div class="socials">
            <?php if (!empty($contact['social']['github'])): ?>
              <a class="social" href="<?php echo sanitize($contact['social']['github']); ?>" target="_blank" rel="noopener" aria-label="GitHub">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M12 .5A12 12 0 0 0 0 12.6c0 5.3 3.4 9.9 8.2 11.5.6.1.8-.3.8-.6v-2c-3.3.7-4-1.6-4-1.6-.6-1.6-1.5-2-1.5-2-1.2-.8.1-.8.1-.8 1.3.1 2 1.4 2 1.4 1.2 2 3.2 1.5 4 .9.1-.9.5-1.5.9-1.9-2.7-.3-5.5-1.4-5.5-6.3 0-1.4.5-2.5 1.3-3.5-.1-.3-.6-1.6.1-3.4 0 0 1.1-.4 3.6 1.3a12.1 12.1 0 0 1 6.5 0c2.5-1.7 3.6-1.3 3.6-1.3.7 1.8.2 3.1.1 3.4.8 1 1.3 2.2 1.3 3.5 0 4.9-2.8 6-5.5 6.3.5.4.9 1.2.9 2.4v3.6c0 .3.2.7.8.6A12.1 12.1 0 0 0 24 12.6 12 12 0 0 0 12 .5z"/></svg>
              </a>
            <?php endif; ?>
            <?php if (!empty($contact['social']['linkedin'])): ?>
              <a class="social" href="<?php echo sanitize($contact['social']['linkedin']); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0zM.2 8.2h4.8V24H.2zM9 8.2h4.6v2.1H13c.6-1.1 2-2.2 4.1-2.2 4.4 0 5.2 2.9 5.2 6.7V24h-4.8v-6.9c0-1.6 0-3.7-2.3-3.7-2.4 0-2.8 1.8-2.8 3.6V24H9z"/></svg>
              </a>
            <?php endif; ?>
            <?php if (!empty($contact['social']['twitter'])): ?>
              <a class="social" href="<?php echo sanitize($contact['social']['twitter']); ?>" target="_blank" rel="noopener" aria-label="Twitter">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M23.4 4.9c-.8.4-1.6.6-2.5.8.9-.6 1.6-1.4 1.9-2.5-.9.6-1.9 1-3 1.2A4.4 4.4 0 0 0 16.3 3c-2.5 0-4.5 2.1-4.5 4.6 0 .4 0 .8.1 1.1-3.7-.2-7-2-9.2-4.9-.4.8-.6 1.6-.6 2.5 0 1.6.8 3 2 3.8-.7 0-1.4-.2-2-.6 0 2.3 1.6 4.2 3.7 4.7-.4.1-.8.2-1.3.2-.3 0-.6 0-.9-.1.6 2 2.5 3.5 4.7 3.5A9 9 0 0 1 0 19.5a12.6 12.6 0 0 0 6.8 2c8.2 0 12.8-7 12.8-13.1v-.6c.9-.6 1.6-1.4 2.2-2.3z"/></svg>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <div>
          <h3>Disponibilité</h3>
          <p>Ouvert aux opportunités et collaborations.</p>
          <a href="#hero" class="btn btn-secondary">Retour en haut</a>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>© <?php echo date('Y'); ?> <?php echo sanitize($general['site_name']); ?> — Construit avec passion.</p>
    </div>
  </footer>

  <button id="backToTop" class="back-to-top" aria-label="Retour en haut">↑</button>

  <script src="assets/js/main.js"></script>
</body>
</html>
