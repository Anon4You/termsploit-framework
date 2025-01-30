<?php

function checkAdminPanels(string $url): array {
    $foundPanels = [];
    $adminPaths = [
        '/admin',
        '/admin.php',
        '/administrator',
        '/admin/login',
        '/login',
        '/wp-admin', // WordPress
        '/user/login', // Drupal
        '/backend', // Common path for various CMS
        '/controlpanel',
        '/manage',
        '/dashboard',
    ];

    foreach ($adminPaths as $path) {
        $response = @file_get_contents($url . $path);
        if ($response !== false) {
            $foundPanels[] = $url . $path;
        }
    }

    return $foundPanels;
}

function parseArguments(): string {
    $options = getopt("u:", ["url:"]);
    $url = isset($options['u']) ? trim($options['u']) : (isset($options['url']) ? trim($options['url']) : '');

    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        die("Invalid or missing URL. Please provide a valid URL using -u or --url.\n");
    }

    return rtrim($url, '/'); // Remove trailing slash if exists
}

function main() {
    $url = parseArguments();
    $foundPanels = checkAdminPanels($url);

    if (!empty($foundPanels)) {
        echo "Admin panels found:\n";
        foreach ($foundPanels as $panel) {
            echo $panel . "\n";
        }
    } else {
        echo "No admin panels found.\n";
    }
}

main();
?>
