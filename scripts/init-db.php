<?php
require __DIR__ . '/../config/bootstrap.php';

use App\Core\Database;

try {
    $pdo = Database::getInstance();

    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->fetchColumn()) {
        fwrite(STDOUT, "Database already initialized; skipping schema import.\n");
        exit(0);
    }

    $sql = file_get_contents(__DIR__ . '/../database/schema-aiven.sql');
    if ($sql === false) {
        throw new RuntimeException('Unable to read database/schema-aiven.sql');
    }

    $pdo->exec($sql);
    fwrite(STDOUT, "Aiven database schema and seed data initialized successfully.\n");
} catch (Throwable $e) {
    fwrite(STDERR, "Database initialization failed: " . $e->getMessage() . "\n");
    exit(1);
}
