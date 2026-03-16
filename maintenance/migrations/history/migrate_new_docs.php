<?php

$dbPath = __DIR__ . '/database/database.sqlite';
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $fields = [
        'doc_nascimento_casamento',
        'doc_nascimento_casamento_status',
        'doc_nascimento_casamento_validade',
        'doc_conjuge_nascimento_casamento',
        'doc_conjuge_nascimento_casamento_status',
        'doc_conjuge_nascimento_casamento_validade'
    ];

    echo "Adicionando novos campos de documentos na tabela associados...\n";

    foreach ($fields as $field) {
        try {
            $type = str_contains($field, 'status') ? "INTEGER DEFAULT 0" : (str_contains($field, 'validade') ? "DATE" : "TEXT");
            $sql = "ALTER TABLE associados ADD COLUMN $field $type";
            $db->exec($sql);
            echo "Campo '$field' adicionado com sucesso.\n";
        } catch (PDOException $e) {
            echo "Campo '$field' pode já existir ou erro: " . $e->getMessage() . "\n";
        }
    }
    
    echo "Migração de novos documentos concluída!\n";

} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
