<?php
require_once __DIR__ . '/config.php';

function ensure_session(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function sanitize(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function read_json(string $file): array {
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function write_json(string $file, array $data): bool {
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    return file_put_contents($file, $json, LOCK_EX) !== false;
}

function get_data(): array {
    $data = read_json(DATA_FILE);

    // Ensure basic structure
    $data['general'] = $data['general'] ?? [
        'site_name' => SITE_NAME,
        'hero_title' => 'Salut, je suis Jean Dupont',
        'hero_subtitle' => 'Développeur Full‑Stack',
        'hero_cta_text' => 'Voir mes projets'
    ];
    $data['about'] = $data['about'] ?? [
        'title' => 'À propos',
        'text' => "Passionné par la création d'expériences web performantes et élégantes.",
        'profile_image' => 'assets/img/placeholder.svg'
    ];
    $data['skills'] = $data['skills'] ?? [
        ['name' => 'JavaScript', 'level' => 90],
        ['name' => 'PHP', 'level' => 85],
        ['name' => 'CSS', 'level' => 88],
    ];
    $data['projects'] = $data['projects'] ?? [
        [
            'title' => 'Projet A',
            'description' => "Application web moderne.",
            'tags' => ['PHP','MySQL'],
            'url' => '#',
            'image' => 'assets/img/placeholder.svg'
        ],
        [
            'title' => 'Projet B',
            'description' => 'Site vitrine élégant.',
            'tags' => ['HTML','CSS','JS'],
            'url' => '#',
            'image' => 'assets/img/placeholder.svg'
        ],
    ];
    $data['contact'] = $data['contact'] ?? [
        'email' => 'contact@example.com',
        'phone' => '+33 6 12 34 56 78',
        'location' => 'Paris, France',
        'social' => [
            'github' => 'https://github.com/username',
            'linkedin' => 'https://linkedin.com/in/username',
            'twitter' => 'https://twitter.com/username'
        ]
    ];

    return $data;
}

function save_data(array $data): bool {
    return write_json(DATA_FILE, $data);
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function csrf_token(): string {
    ensure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool {
    ensure_session();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_meta(): void {
    echo '<meta name="csrf-token" content="' . sanitize(csrf_token()) . '">';
}

function json_response(array $payload, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    exit;
}
?>
