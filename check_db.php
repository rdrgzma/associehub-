<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $stmt = $db->query("PRAGMA table_info(associados)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($columns, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
