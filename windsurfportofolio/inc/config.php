<?php
// Basic site configuration
// IMPORTANT: Change ADMIN_USER and ADMIN_PASSWORD after first login.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site name displayed in the header and admin
const SITE_NAME = 'Mon Portfolio';

// Data and uploads
const DATA_FILE = __DIR__ . '/../data/site.json';
const UPLOAD_DIR = __DIR__ . '/../uploads';

// Admin credentials (default). Change immediately in this file after setup.
const ADMIN_USER = 'admin';
const ADMIN_PASSWORD = 'admin123'; // À CHANGER dès que possible

// Max upload size (in bytes)
const MAX_UPLOAD_SIZE = 5 * 1024 * 1024; // 5 MB

// Allowed upload extensions
const ALLOWED_EXTS = ['jpg','jpeg','png','gif','webp','svg'];

// Utility: Base URL (best-effort for linking if needed)
function base_url(string $path = ''): string {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $root = $scriptDir === '/' ? '' : $scriptDir;
    return $scheme . '://' . $host . $root . '/' . ltrim($path, '/');
}

?>
