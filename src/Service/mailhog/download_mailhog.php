<?php

// Détecter le système d'exploitation
$os = PHP_OS_FAMILY;

// Configuration selon le système d'exploitation
if ($os === 'Windows') {
    $filename = 'MailHog.exe';
    $mailhogUrl = 'https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_windows_amd64.exe';
} elseif ($os === 'Darwin') {  // macOS
    $filename = 'MailHog';
    $mailhogUrl = 'https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_darwin_amd64';
} elseif ($os === 'Linux') {
    $filename = 'MailHog';
    $mailhogUrl = 'https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_linux_amd64';
} else {
    die("Système d'exploitation non supporté");
}

// Le chemin où MailHog sera stocké
$dir = __DIR__ . '/mailhog';
$fullPath = $dir . '/' . $filename;

// Crée le dossier mailhog s'il n'existe pas
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Vérifie si MailHog est déjà téléchargé
if (!file_exists($fullPath)) {
    echo "Téléchargement de MailHog pour $os...\n";

    // Téléchargement de MailHog
    $fileContent = file_get_contents($mailhogUrl);
    file_put_contents($fullPath, $fileContent);

    echo "Téléchargement terminé.\n";
} else {
    echo "MailHog est déjà téléchargé.\n";
}

// Donne les permissions d'exécution au fichier téléchargé (important pour macOS/Linux)
if ($os !== 'Windows') {
    chmod($fullPath, 0755);
}

// Lancer MailHog
echo "Lancement de MailHog à partir de : " . realpath($fullPath) . "\n";

if ($os === 'Windows') {
    shell_exec('start "" "' . realpath($fullPath) . '"');
} else {
    shell_exec(realpath($fullPath) . ' > /dev/null 2>&1 &');
}

echo "MailHog est lancé sur http://localhost:8025\n";
