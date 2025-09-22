<?php
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf($token)) {
    redirect('index.php');
}

$posted = $_POST;

$new = [
    'general' => [
        'site_name' => trim($posted['general']['site_name'] ?? SITE_NAME),
        'hero_title' => trim($posted['general']['hero_title'] ?? ''),
        'hero_subtitle' => trim($posted['general']['hero_subtitle'] ?? ''),
        'hero_cta_text' => trim($posted['general']['hero_cta_text'] ?? 'Voir mes projets'),
    ],
    'about' => [
        'title' => trim($posted['about']['title'] ?? 'À propos'),
        'text' => trim($posted['about']['text'] ?? ''),
        'profile_image' => trim($posted['about']['profile_image'] ?? 'assets/img/placeholder.svg'),
    ],
    'skills' => [],
    'projects' => [],
    'contact' => [
        'email' => trim($posted['contact']['email'] ?? ''),
        'phone' => trim($posted['contact']['phone'] ?? ''),
        'location' => trim($posted['contact']['location'] ?? ''),
        'social' => [
            'github' => trim($posted['contact']['social']['github'] ?? ''),
            'linkedin' => trim($posted['contact']['social']['linkedin'] ?? ''),
            'twitter' => trim($posted['contact']['social']['twitter'] ?? ''),
        ],
    ],
];

// Skills
if (!empty($posted['skills']) && is_array($posted['skills'])) {
    foreach ($posted['skills'] as $s) {
        $name = trim($s['name'] ?? '');
        $level = (int)($s['level'] ?? 0);
        if ($name !== '') {
            $new['skills'][] = ['name' => $name, 'level' => max(0, min(100, $level))];
        }
    }
}

// Projects
if (!empty($posted['projects']) && is_array($posted['projects'])) {
    foreach ($posted['projects'] as $p) {
        $title = trim($p['title'] ?? '');
        if ($title === '') continue;
        $desc = trim($p['description'] ?? '');
        $url = trim($p['url'] ?? '');
        $image = trim($p['image'] ?? 'assets/img/placeholder.svg');
        $tagsStr = trim($p['tags'] ?? '');
        $tags = $tagsStr === '' ? [] : array_values(array_filter(array_map(function($t){return trim($t);}, explode(',', $tagsStr))));
        $new['projects'][] = [
            'title' => $title,
            'description' => $desc,
            'tags' => $tags,
            'url' => $url === '' ? '#' : $url,
            'image' => $image,
        ];
    }
}

if (save_data($new)) {
    redirect('index.php?ok=1');
} else {
    redirect('index.php?ok=0');
}
