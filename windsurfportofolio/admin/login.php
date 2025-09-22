<?php
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/auth.php';

$error = '';
// If already logged in, go directly to dashboard
if (is_logged_in()) {
    redirect('index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $error = 'Jeton CSRF invalide. Veuillez réessayer.';
    } else {
        $u = trim($_POST['username'] ?? '');
        $p = trim($_POST['password'] ?? '');
        if (login($u, $p)) {
            redirect('index.php');
        } else {
            $error = 'Identifiants incorrects.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Connexion</title>
  <?php csrf_meta(); ?>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
  <div class="admin-login">
    <h1>Connexion Admin</h1>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo sanitize($error); ?></div>
    <?php endif; ?>
    <form method="post" class="card">
      <input type="hidden" name="csrf_token" value="<?php echo sanitize(csrf_token()); ?>">
      <div class="form-group">
        <label>Utilisateur</label>
        <input type="text" name="username" required>
      </div>
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" required>
      </div>
      <button class="btn btn-primary btn-glow" type="submit">Se connecter</button>
      <a href="../index.php" class="btn btn-secondary" style="margin-left:8px">← Retour au site</a>
      <p class="hint">Par défaut: admin / admin123 — à modifier dans <code>inc/config.php</code></p>
    </form>
  </div>
</body>
</html>
