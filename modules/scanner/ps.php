<?php

/**
 * Scans a range of ports on a specified host to check for open ports.
 *
 * @param string $host The target host.
 * @param int $startPort The starting port number.
 * @param int $endPort The ending port number.
 * @return array An array of open ports.
 */
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

/**
 * Displays the results of the port scan.
 *
 * @param string $host The target host.
 * @param array $openPorts An array of open ports.
 */
function displayResults(string $host, array $openPorts): void {
    if (!empty($openPorts)) {
        echo "Open ports on $host:\n";
        foreach ($openPorts as $port) {
            echo "Port $port is open.\n";
        }
    } else {
        echo "No open ports found on $host.\n";
    }
}

/**
 * Validates the command-line arguments and sets default values if necessary.
 *
 * @return array An associative array containing host, startPort, and endPort.
 */
function parseArguments(): array {
    $options = getopt("h:p:e:", ["host:", "start:", "end:"]);

    $host = $options['h'] ?? $options['host'] ?? '127.0.0.1';
    $startPort = isset($options['p']) ? (int)$options['p'] : (isset($options['start']) ? (int)$options['start'] : 1);
    $endPort = isset($options['e']) ? (int)$options['e'] : (isset($options['end']) ? (int)$options['end'] : 1024);

    return [
        'host' => $host,
        'startPort' => $startPort,
        'endPort' => $endPort,
    ];
}

// Main script execution
$arguments = parseArguments();
$openPorts = scanPorts($arguments['host'], $arguments['startPort'], $arguments['endPort']);
displayResults($arguments['host'], $openPorts);
?>
