<?php
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();
$data = get_data();
$general = $data['general'];
$about = $data['about'];
$skills = $data['skills'];
$projects = $data['projects'];
$contact = $data['contact'];
$ok = isset($_GET['ok']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administration — <?php echo sanitize($general['site_name']); ?></title>
  <?php csrf_meta(); ?>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
<header class="admin-header">
  <div class="container admin-nav">
    <div class="brand">Admin — <?php echo sanitize($general['site_name']); ?></div>
    <nav>
      <a href="../index.php" target="_blank">↗ Voir le site</a>
      <a href="logout.php">Se déconnecter</a>
    </nav>
  </div>
</header>

<main class="container admin-main">
  <?php if ($ok): ?>
    <div class="alert alert-success">Modifications enregistrées avec succès.</div>
  <?php endif; ?>

  <div class="tabs">
    <button class="tab active" data-tab="tab-general">Général</button>
    <button class="tab" data-tab="tab-about">À propos</button>
    <button class="tab" data-tab="tab-skills">Compétences</button>
    <button class="tab" data-tab="tab-projects">Projets</button>
    <button class="tab" data-tab="tab-contact">Contact</button>
  </div>

  <form class="admin-form" method="post" action="save.php">
    <input type="hidden" name="csrf_token" value="<?php echo sanitize(csrf_token()); ?>">

    <section id="tab-general" class="tab-panel active">
      <div class="card">
        <div class="form-group">
          <label>Nom du site</label>
          <input type="text" name="general[site_name]" value="<?php echo sanitize($general['site_name']); ?>" required>
        </div>
        <div class="form-grid-2">
          <div class="form-group">
            <label>Titre Héro</label>
            <input type="text" name="general[hero_title]" value="<?php echo sanitize($general['hero_title']); ?>" required>
          </div>
          <div class="form-group">
            <label>Sous-titre</label>
            <input type="text" name="general[hero_subtitle]" value="<?php echo sanitize($general['hero_subtitle']); ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Bouton CTA</label>
          <input type="text" name="general[hero_cta_text]" value="<?php echo sanitize($general['hero_cta_text']); ?>">
        </div>
      </div>
    </section>

    <section id="tab-about" class="tab-panel">
      <div class="card">
        <div class="form-group">
          <label>Titre</label>
          <input type="text" name="about[title]" value="<?php echo sanitize($about['title']); ?>">
        </div>
        <div class="form-group">
          <label>Texte</label>
          <textarea name="about[text]" rows="5"><?php echo sanitize($about['text']); ?></textarea>
        </div>
        <div class="form-group">
          <label>Image de profil</label>
          <div class="image-input">
            <img src="../<?php echo sanitize($about['profile_image']); ?>" alt="Aperçu" class="preview" onerror="this.src='../assets/img/placeholder.svg'">
            <input type="hidden" name="about[profile_image]" value="<?php echo sanitize($about['profile_image']); ?>">
            <input type="file" class="file" accept="image/*">
            <button type="button" class="btn btn-secondary upload-btn">Téléverser</button>
          </div>
        </div>
      </div>
    </section>

    <section id="tab-skills" class="tab-panel">
      <div class="card">
        <div class="list-header">
          <h3>Compétences</h3>
          <button type="button" class="btn btn-primary add-skill">+ Ajouter</button>
        </div>
        <div class="skills-list">
          <?php foreach ($skills as $i => $s): ?>
            <div class="item skill-item">
              <input type="text" name="skills[<?php echo $i; ?>][name]" value="<?php echo sanitize($s['name']); ?>" placeholder="Nom" required>
              <input type="number" min="0" max="100" name="skills[<?php echo $i; ?>][level]" value="<?php echo (int)$s['level']; ?>" placeholder="Niveau (%)" required>
              <button type="button" class="btn btn-secondary remove">Supprimer</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="tab-projects" class="tab-panel">
      <div class="card">
        <div class="list-header">
          <h3>Projets</h3>
          <button type="button" class="btn btn-primary add-project">+ Ajouter</button>
        </div>
        <div class="projects-list">
          <?php foreach ($projects as $i => $p): ?>
            <div class="item project-item">
              <div class="form-grid-2">
                <input type="text" name="projects[<?php echo $i; ?>][title]" value="<?php echo sanitize($p['title']); ?>" placeholder="Titre" required>
                <input type="text" name="projects[<?php echo $i; ?>][url]" value="<?php echo sanitize($p['url']); ?>" placeholder="URL (optionnel)">
              </div>
              <div class="form-group">
                <textarea name="projects[<?php echo $i; ?>][description]" rows="3" placeholder="Description"><?php echo sanitize($p['description']); ?></textarea>
              </div>
              <div class="form-group">
                <label>Tags (séparés par des virgules)</label>
                <input type="text" name="projects[<?php echo $i; ?>][tags]" value="<?php echo sanitize(implode(', ', $p['tags'] ?? [])); ?>" placeholder="ex: PHP, MySQL">
              </div>
              <div class="form-group">
                <label>Image</label>
                <div class="image-input">
                  <img src="../<?php echo sanitize($p['image']); ?>" alt="Aperçu" class="preview" onerror="this.src='../assets/img/placeholder.svg'">
                  <input type="hidden" name="projects[<?php echo $i; ?>][image]" value="<?php echo sanitize($p['image']); ?>">
                  <input type="file" class="file" accept="image/*">
                  <button type="button" class="btn btn-secondary upload-btn">Téléverser</button>
                </div>
              </div>
              <hr>
              <button type="button" class="btn btn-secondary remove">Supprimer ce projet</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="tab-contact" class="tab-panel">
      <div class="card">
        <div class="form-grid-3">
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="contact[email]" value="<?php echo sanitize($contact['email']); ?>">
          </div>
          <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="contact[phone]" value="<?php echo sanitize($contact['phone']); ?>">
          </div>
          <div class="form-group">
            <label>Localisation</label>
            <input type="text" name="contact[location]" value="<?php echo sanitize($contact['location']); ?>">
          </div>
        </div>
        <div class="form-grid-3">
          <div class="form-group">
            <label>GitHub</label>
            <input type="url" name="contact[social][github]" value="<?php echo sanitize($contact['social']['github']); ?>">
          </div>
          <div class="form-group">
            <label>LinkedIn</label>
            <input type="url" name="contact[social][linkedin]" value="<?php echo sanitize($contact['social']['linkedin']); ?>">
          </div>
          <div class="form-group">
            <label>Twitter</label>
            <input type="url" name="contact[social][twitter]" value="<?php echo sanitize($contact['social']['twitter']); ?>">
          </div>
        </div>
      </div>
    </section>

    <div class="actions">
      <button class="btn btn-primary btn-glow" type="submit">Enregistrer</button>
    </div>
  </form>

  <!-- Templates -->
  <template id="tpl-skill">
    <div class="item skill-item">
      <input type="text" name="__REPLACE__" value="" placeholder="Nom" required>
      <input type="number" min="0" max="100" name="__REPLACE__" value="" placeholder="Niveau (%)" required>
      <button type="button" class="btn btn-secondary remove">Supprimer</button>
    </div>
  </template>

  <template id="tpl-project">
    <div class="item project-item">
      <div class="form-grid-2">
        <input type="text" name="__REPLACE__" value="" placeholder="Titre" required>
        <input type="text" name="__REPLACE__" value="" placeholder="URL (optionnel)">
      </div>
      <div class="form-group">
        <textarea name="__REPLACE__" rows="3" placeholder="Description"></textarea>
      </div>
      <div class="form-group">
        <label>Tags (séparés par des virgules)</label>
        <input type="text" name="__REPLACE__" value="" placeholder="ex: PHP, MySQL">
      </div>
      <div class="form-group">
        <label>Image</label>
        <div class="image-input">
          <img src="../assets/img/placeholder.svg" alt="Aperçu" class="preview">
          <input type="hidden" name="__REPLACE__" value="assets/img/placeholder.svg">
          <input type="file" class="file" accept="image/*">
          <button type="button" class="btn btn-secondary upload-btn">Téléverser</button>
        </div>
      </div>
      <hr>
      <button type="button" class="btn btn-secondary remove">Supprimer ce projet</button>
    </div>
  </template>
</main>

<script src="../assets/js/admin.js"></script>
</body>
</html>
