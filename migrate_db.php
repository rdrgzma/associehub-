<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $missingColumns = [
        'numero' => 'TEXT',
        'complemento' => 'TEXT',
        'bairro' => 'TEXT',
        'cep' => 'TEXT',
        'nacionalidade' => 'TEXT',
        'naturalidade' => 'TEXT',
        'data_nascimento' => 'DATE',
        'idade' => 'INTEGER',
        'rg' => 'TEXT',
        'rg_orgao_emissor' => 'TEXT',
        'filiacao_1_nome' => 'TEXT',
        'filiacao_1_cpf' => 'TEXT',
        'filiacao_2_nome' => 'TEXT',
        'filiacao_2_cpf' => 'TEXT',
        'estado_civil' => 'TEXT',
        'forma_comunhao' => 'TEXT',
        'conjuge_nome' => 'TEXT',
        'conjuge_cpf' => 'TEXT',
        'profissao_1' => 'TEXT',
        'profissao_1_registro' => 'TEXT',
        'profissao_1_orgao' => 'TEXT',
        'profissao_2' => 'TEXT',
        'profissao_2_registro' => 'TEXT',
        'profissao_2_orgao' => 'TEXT'
    ];

    foreach ($missingColumns as $column => $type) {
        try {
            $db->exec("ALTER TABLE associados ADD COLUMN $column $type");
            echo "Added column: $column\n";
        } catch (PDOException $e) {
            // Probably already exists, skip
            echo "Skipped column (might exist): $column - " . $e->getMessage() . "\n";
        }
    }

    echo "Migration completed successfully.";
} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage();
}
