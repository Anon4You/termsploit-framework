<?php

function scanPorts(string $host, int $startPort, int $endPort): array {
    $openPorts = [];

    for ($port = $startPort; $port <= $endPort; $port++) {
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if (is_resource($connection)) {
            $openPorts[] = $port;
            fclose($connection);
        }
    }

    return $openPorts;
}

function checkVulnerabilities(string $url): array {
    $vulnerabilities = [];

    $commonFiles = [
        'robots.txt',
        'wp-config.php',
        'phpinfo.php',
        'admin.php',
        'config.php',
    ];

    foreach ($commonFiles as $file) {
        $response = @file_get_contents($url . '/' . $file);
        if ($response !== false) {
            $vulnerabilities[] = "Found common file: " . $file;
        }
    }

    $sqlPayloads = [
        "' OR '1'='1",
        "' OR '1'='1' -- ",
        "'; DROP TABLE users; --",
    ];

    foreach ($sqlPayloads as $payload) {
        $response = @file_get_contents($url . "?id=" . urlencode($payload));
        if ($response !== false && strpos($response, 'error') !== false) {
            $vulnerabilities[] = "Potential SQL Injection vulnerability found with payload: " . $payload;
        }
    }

    $xssPayload = "<script>alert('XSS');</script>";
    $response = @file_get_contents($url . "?input=" . urlencode($xssPayload));
    if ($response !== false && strpos($response, 'XSS') !== false) {
        $vulnerabilities[] = "Potential XSS vulnerability found with payload: " . $xssPayload;
    }

    return $vulnerabilities;
}

function parseArguments(): array {
    $options = getopt("u:", ["url:"]);
    $url = isset($options['u']) ? trim($options['u']) : (isset($options['url']) ? trim($options['url']) : '');

    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        die("Invalid or missing URL. Please provide a valid URL using -u or --url.\n");
    }

    return [$url]; // Return an array with the URL
}

function main() {
    $args = parseArguments();
    $url = $args[0]; // Get the URL from the array
    $host = parse_url($url, PHP_URL_HOST);
    $startPort = 1;
    $endPort = 1024;

    $openPorts = scanPorts($host, $startPort, $endPort);
    if (!empty($openPorts)) {
        echo "Open ports on $host:\n";
        foreach ($openPorts as $port) {
            echo "Port $port is open.\n";
        }
    } else {
        echo "No open ports found on $host.\n";
    }

    $vulnerabilities = checkVulnerabilities($url);
    if (!empty($vulnerabilities)) {
        echo "Vulnerabilities found:\n";
        foreach ($vulnerabilities as $vulnerability) {
            echo $vulnerability . "\n";
        }
    } else {
        echo "No vulnerabilities found.\n";
    }
}

main();
?>
