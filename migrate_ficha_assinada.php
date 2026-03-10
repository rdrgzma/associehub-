<?php
$dbPath = __DIR__ . '/database/database.sqlite';

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectando ao banco de dados SQLite em: $dbPath\n";

    // Adding columns for the completed Printed / Signed Association Registration Sheet
    $columnsToAdd = [
        'doc_ficha_assinada' => 'TEXT',
        'doc_ficha_assinada_status' => 'INTEGER DEFAULT 0',
        'doc_ficha_assinada_validade' => 'DATE'
    ];

    foreach ($columnsToAdd as $colName => $colType) {
        try {
            $check = $pdo->query("SELECT $colName FROM associados LIMIT 1");
            echo "A coluna '$colName' já existe na tabela 'associados'.\n";
        } catch(PDOException $e) {
            $sql = "ALTER TABLE associados ADD COLUMN $colName $colType;";
            $pdo->exec($sql);
            echo "Coluna adicionada: $colName ($colType)\n";
        }
    }

    echo "Migração completada.\n";

} catch (PDOException $e) {
    echo "Erro de conexão ou execução: " . $e->getMessage() . "\n";
}
