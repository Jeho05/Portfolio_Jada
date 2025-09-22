<?php
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Méthode non autorisée'], 405);
}

$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf($token)) {
    json_response(['error' => 'CSRF invalide'], 400);
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    json_response(['error' => 'Aucun fichier'], 400);
}

$f = $_FILES['file'];
if ($f['size'] > MAX_UPLOAD_SIZE) {
    json_response(['error' => 'Fichier trop volumineux'], 400);
}

$ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ALLOWED_EXTS, true)) {
    json_response(['error' => 'Extension non autorisée'], 400);
}

// Validate MIME type strictly
$allowedMimes = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
    'svg'  => 'image/svg+xml',
];

$mime = '';
if ($ext === 'svg') {
    // Basic SVG validation: ensure file starts with an <svg tag
    $head = @file_get_contents($f['tmp_name'], false, null, 0, 512);
    if ($head === false || stripos($head, '<svg') === false) {
        json_response(['error' => 'Fichier SVG invalide'], 400);
    }
    $mime = 'image/svg+xml';
} else {
    if (function_exists('finfo_open')) {
        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mime = @finfo_file($finfo, $f['tmp_name']) ?: '';
            @finfo_close($finfo);
        }
    }
    if ($mime === '') {
        $info = @getimagesize($f['tmp_name']);
        if ($info && !empty($info['mime'])) {
            $mime = $info['mime'];
        }
    }
}

if ($mime === '' || (isset($allowedMimes[$ext]) && $mime !== $allowedMimes[$ext])) {
    json_response(['error' => 'Type de fichier invalide'], 400);
}

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0775, true);
}

$base = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', pathinfo($f['name'], PATHINFO_FILENAME));
$filename = $base . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$dest = UPLOAD_DIR . '/' . $filename;

if (!move_uploaded_file($f['tmp_name'], $dest)) {
    json_response(['error' => 'Échec du téléversement'], 500);
}

$relative = 'uploads/' . $filename;
json_response(['path' => $relative]);
