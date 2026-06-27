<?php


// Path to the SQLite database file.
// Stored outside the web root is ideal; adjust as needed.
$db_path = __DIR__ . '/../config/database.sqlite';

if (!file_exists(dirname($db_path))) {
    // config/ folder must exist and be writable
    echo "<p><strong>Application not installed!</strong> <a href='install.php'>Click here</a> to proceed with installation.</p>";
    exit();
}

if (!file_exists($db_path) && basename($_SERVER['SCRIPT_FILENAME']) !== 'install.php') {
    echo "<p><strong>Application not installed!</strong> <a href='install.php'>Click here</a> to proceed with installation.</p>";
    exit();
}

try {
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Enable WAL mode for better concurrent read performance
    $pdo->exec('PRAGMA journal_mode=WAL');
    $pdo->exec('PRAGMA foreign_keys=ON');
} catch (PDOException $e) {
    die("<p><strong>Database connection failed:</strong> " . htmlspecialchars($e->getMessage()) . "</p>");
}