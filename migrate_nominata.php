<?php

$dbPath = __DIR__ . '/database/database.sqlite';
if (!file_exists($dbPath)) {
    // Try alternate paths if needed
    $dbPath = __DIR__ . '/database/associehub.db';
}

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS associacao_nominata (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        associacao_id INTEGER NOT NULL,
        associado_id INTEGER,
        cargo TEXT NOT NULL,
        ordem INTEGER DEFAULT 0,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (associacao_id) REFERENCES associacoes(id) ON DELETE CASCADE,
        FOREIGN KEY (associado_id) REFERENCES associados(id) ON DELETE SET NULL
    )";

    $db->exec($sql);
    echo "Table 'associacao_nominata' created successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
