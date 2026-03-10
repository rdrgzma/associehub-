<?php

$dbPath = __DIR__ . '/database/database.sqlite';
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $fields = [
        'doc_conjuge_identidade', 'doc_conjuge_quitacao_eleitoral', 'doc_conjuge_fiscal_federal', 
        'doc_conjuge_fiscal_estadual', 'doc_conjuge_fiscal_municipal', 'doc_conjuge_situacao_cpf',
        'doc_conjuge_identidade_status', 'doc_conjuge_quitacao_eleitoral_status', 'doc_conjuge_fiscal_federal_status',
        'doc_conjuge_fiscal_estadual_status', 'doc_conjuge_fiscal_municipal_status', 'doc_conjuge_situacao_cpf_status',
        'doc_conjuge_identidade_validade', 'doc_conjuge_quitacao_eleitoral_validade', 'doc_conjuge_fiscal_federal_validade',
        'doc_conjuge_fiscal_estadual_validade', 'doc_conjuge_fiscal_municipal_validade', 'doc_conjuge_situacao_cpf_validade'
    ];

    echo "Adicionando campos na tabela...\n";

    foreach ($fields as $field) {
        try {
            // SQLite doesn't have IF NOT EXISTS for ALTER TABLE ADD COLUMN natively in simple commands
            // Using a simple try-catch to ignore duplicate column errors if script runs multiple times
            $type = str_contains($field, 'status') ? "INTEGER DEFAULT 0" : "TEXT";
            $sql = "ALTER TABLE associados ADD COLUMN $field $type";
            $db->exec($sql);
            echo "Successfully added column $field\n";
        } catch (PDOException $e) {
            echo "Column $field might already exist or error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "Migracao Completa!";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
